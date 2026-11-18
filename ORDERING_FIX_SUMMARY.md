# Excel Export Ordering Fix - Summary

## Issue
The Excel export from the Export page showed data in a different order compared to the DPR page, causing the "No." column to display different sequences for the same records.

## Root Cause
Even though both pages use the same `tampil_production_rev()` function with the same SQL `ORDER BY` clause, the data array was not being consistently sorted when processed in the export function.

## Solution Implemented

### 1. Added Explicit Sorting in Export Controller
**File**: `application/controllers/c_export.php`

Added a `usort()` function before generating the Excel to ensure the data is sorted exactly the same way as the SQL query:

```php
// Sort data to match DPR page ordering (cek_kanit DESC, tanggal ASC, mesin ASC)
usort($data, function($a, $b) {
    // First sort by cek_kanit (DESC - verified first)
    if ($a['cek_kanit'] != $b['cek_kanit']) {
        return ($b['cek_kanit'] - $a['cek_kanit']);
    }
    // Then sort by tanggal (ASC)
    if ($a['tanggal'] != $b['tanggal']) {
        return strcmp($a['tanggal'], $b['tanggal']);
    }
    // Finally sort by mesin (ASC)
    return strcmp($a['mesin'], $b['mesin']);
});
```

This ensures the data is sorted by:
1. **cek_kanit** (DESC) - Verified records first
2. **tanggal** (ASC) - Chronological order
3. **mesin** (ASC) - Alphabetical order

### 2. Added Logging for Verification
Both controllers now log the first 5 IDs to verify the order:

**Export Page**:
```php
$first_ids = array_slice(array_column($data, 'id'), 0, 5);
log_message('info', 'EXPORT PAGE - First 5 IDs after sorting: ' . implode(', ', $first_ids));
```

**DPR Page**:
```php
$first_ids = array_slice(array_column($result_array, 'id'), 0, 5);
log_message('info', "DPR PAGE - First 5 IDs: " . implode(', ', $first_ids));
```

## How to Verify the Fix

1. **Export from both pages** using the same date range and shift
2. **Check the logs** at `\application\logs\log-YYYY-MM-DD.php`
3. **Compare the first 5 IDs** - they should now match
4. **Open both Excel files** and verify:
   - The "No." column shows the same sequence
   - The same record (by ID) has the same "No." in both files

## Expected Behavior

### Before Fix
- DPR Page: Record ID 1234 at No. 1
- Export Page: Record ID 5678 at No. 1
- ❌ Different order

### After Fix
- DPR Page: Record ID 1234 at No. 1
- Export Page: Record ID 1234 at No. 1
- ✅ Same order

## Technical Details

### SQL ORDER BY Clause
The database query already has the correct ordering:
```sql
ORDER BY q.`cek_kanit` DESC, q.`tanggal`, q.`mesin` ASC
```

The PHP `usort()` function replicates this exact same logic to ensure consistency even if the array order changes during processing.

### Sorting Logic
1. **cek_kanit DESC**: Records with `cek_kanit = 1` (verified) appear first
2. **tanggal ASC**: Older dates appear before newer dates
3. **mesin ASC**: Machine names in alphabetical order

## Files Modified

1. ✅ `application/controllers/c_export.php` - Added sorting and logging
2. ✅ `application/controllers/c_dpr.php` - Added logging for comparison

## Testing Checklist

- [ ] Export from DPR page with date range 2026-01-01 to 2026-01-31
- [ ] Export from Export page with same date range
- [ ] Check logs to verify first 5 IDs match
- [ ] Compare "No." column in both Excel files
- [ ] Verify same records have same "No." in both files
- [ ] Confirm file sizes are similar (if data is identical)

## Notes

- The sorting is now **explicitly enforced** in PHP, not relying on database order
- This ensures **consistent behavior** regardless of how the array is processed
- The logging helps **debug any future ordering issues**
- Both exports should now produce **identically ordered data**
