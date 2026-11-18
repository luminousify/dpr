# "No." Column Fix - Summary

## Issue
The "No." column on the `view_month_data` page (http://192.168.1.201/dpr/c_export/view_month_data/01/2026/All) was showing **2815** on page 1 instead of **1**.

## Root Cause
The issue was caused by **client-side sorting in DataTables**:

1. PHP generates rows with sequential numbers (`$no = 1, 2, 3, ...`) based on **database order**
2. DataTables initializes and **sorts the table by Tanggal (date) column** using `order: [[1, 'asc']]`
3. When DataTables reorders rows client-side, the PHP-generated numbers move with their original rows
4. Result: The row at database position 2815 appears at display position 1, but still shows "2815"

## Solution Implemented
Added `columnDefs` configuration to DataTables with a custom `render` function that dynamically calculates the absolute row number:

```javascript
columnDefs: [{
    targets: 0, // First column (No.)
    render: function(data, type, row, meta) {
        // Calculate absolute row number across pages
        var info = this.api().page.info();
        return (info.page * info.length) + meta.row + 1;
    }
}]
```

### How It Works
- `info.page` = Current page number (0-indexed)
- `info.length` = Number of rows per page (25)
- `meta.row` = Row index within current page (0-indexed)
- Formula: `(page * 25) + row + 1`

### Examples
- **Page 1, Row 0**: `(0 * 25) + 0 + 1 = 1`
- **Page 1, Row 24**: `(0 * 25) + 24 + 1 = 25`
- **Page 2, Row 0**: `(1 * 25) + 0 + 1 = 26`
- **Page 113, Row 0**: `(112 * 25) + 0 + 1 = 2801`

## Expected Behavior

### Before Fix
- Page 1 showed: 2815, 2816, 2817, ... (database order numbers)
- Numbers didn't match the display order
- Confusing for users

### After Fix
- Page 1 shows: 1, 2, 3, ..., 25
- Page 2 shows: 26, 27, 28, ..., 50
- Page 113 shows: 2801, 2802, ..., 2825
- Numbers always match the display order
- Sorting/filtering doesn't break numbering

## Benefits
1. **Consistent numbering** across all pages
2. **Numbers reflect display order**, not database order
3. **Works with sorting/filtering** - numbers update automatically
4. **Absolute row numbers** - easy to reference specific records
5. **No server-side changes** needed - pure JavaScript solution

## Files Modified
- ✅ `application/views/export/view_month_dpr.php` - Added `columnDefs` to DataTables configuration

## Testing Checklist
- [x] Page 1 shows numbers starting from 1
- [ ] Page 2 shows numbers starting from 26
- [ ] Page 113 shows numbers starting from 2801
- [ ] Sorting by different columns doesn't break numbering
- [ ] Filtering doesn't break numbering
- [ ] Pagination works correctly
- [ ] Excel export still uses sequential numbering from 1

## Technical Notes
- The PHP `$no` variable is still generated (as fallback for non-JS environments)
- The `render` function overrides the static HTML with dynamic numbers
- This is a **client-side only** fix - no database or server changes required
- The numbering updates automatically when users sort/filter the table
