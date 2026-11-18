# Export vs DPR Page Debugging Guide

## Problem Statement
Excel exports from the DPR page and Export page produce different file sizes and different data, even when using the same user account and date range.

## Root Cause Analysis

### The Issue
Both the DPR page and Export page call the **exact same function**: `tampil_production_rev($dari, $sampai, $shift)`

However, this function includes **session-based filtering**:
```php
$nama = $_SESSION['nama_actor'];
if ($_SESSION['posisi'] == 'kanit') {
    // Query includes: AND q.kanit = '$nama'
}
```

This means:
- If you're logged in as a **kanit** user, you only see records assigned to you
- Different kanit users will see different data sets
- File sizes will differ based on how many records each kanit has

## What We've Added: Comprehensive Logging

### 1. Export Page Logging (c_export.php)
```php
log_message('info', "EXPORT PAGE - Session: nama_actor=$session_nama, posisi=$session_posisi");
log_message('info', "EXPORT PAGE - Date range: $dari to $sampai, shift=$shift");
log_message('info', "EXPORT PAGE - Query returned $row_count rows");
```

### 2. DPR Page Logging (c_dpr.php)
```php
log_message('info', "DPR PAGE - Session: nama_actor=$session_nama, posisi=$session_posisi");
log_message('info', "DPR PAGE - Date range: $dari to $sampai, shift=$shift");
log_message('info', "DPR PAGE - Query returned $row_count rows");
```

## How to Use This Logging

### Step 1: Check Your Logs
CodeIgniter logs are located at:
```
\application\logs\log-YYYY-MM-DD.php
```

Look for entries like:
```
INFO - EXPORT PAGE - Session: nama_actor=John Doe, posisi=kanit
INFO - EXPORT PAGE - Date range: 2026-01-01 to 2026-01-31, shift=All
INFO - EXPORT PAGE - Query returned 1250 rows
```

### Step 2: Compare the Logs
When you export from both pages, check the logs:

**If row counts match:**
- The data is the same
- File size difference is due to formatting/styling
- This is normal and acceptable

**If row counts differ:**
- Check the session values (nama_actor, posisi)
- Check the date ranges
- Check the shift parameter
- Something is different between the two requests

### Step 3: Verify Session State
Open your browser console (F12) and check:
1. **Application/Storage tab** → Cookies → `ci_session`
2. Compare the session ID between DPR page and Export page
3. Make sure you're logged in as the SAME user

## Possible Scenarios

### Scenario 1: Different Row Counts
**Symptoms:**
- Export page: 500 rows
- DPR page: 1250 rows

**Possible Causes:**
1. **Session changed** between exports (logged out/in as different user)
2. **Date range** calculated differently
3. **Shift parameter** different (All vs Morning vs Evening)

**Solution:**
1. Clear browser cache and logout
2. Login again as the same user
3. Export from both pages WITHOUT doing anything else
4. Check logs immediately

### Scenario 2: Same Row Counts, Different File Sizes
**Symptoms:**
- Both exports: 1250 rows
- File sizes: 631 KB vs 4.5 MB

**Possible Causes:**
1. Different column widths (auto-sizing)
2. Different cell content (more/less text)
3. Different styling/formatting

**Solution:**
This is usually acceptable. The data is the same, just formatted differently.

### Scenario 3: Session Filter Issues
**Symptoms:**
- Export shows 150 rows (only your kanit's data)
- Expecting 1250 rows (all kanits' data)

**Cause:**
You're logged in as a **kanit** user, which filters data to only show your records.

**Solutions:**
1. **Login as a non-kanit user** (kasi, manager, admin)
2. **Create a special export user** with position != 'kanit'
3. **Modify the query** to ignore session filtering for exports (requires code change)

## Testing Steps

### Test 1: Verify Same Data
1. Login as the same user
2. Export from DPR page
3. Export from Export page
4. Check the last row number in both Excel files
5. If they match, data is identical

### Test 2: Check Logs
1. Open `\application\logs\log-2026-02-02.php` (today's date)
2. Search for "EXPORT PAGE" and "DPR PAGE"
3. Compare:
   - Session names
   - Date ranges
   - Row counts

### Test 3: Direct Database Query
Run this SQL to see the actual data:
```sql
SELECT COUNT(*) FROM v_production_op
WHERE tanggal BETWEEN '2026-01-01' AND '2026-01-31'
```

With kanit filter:
```sql
SELECT COUNT(*) FROM v_production_op
WHERE tanggal BETWEEN '2026-01-01' AND '2026-01-31'
AND kanit = 'YOUR_NAME'
```

## Quick Reference

### File Locations
- Export Controller: `\application\controllers\c_export.php`
- DPR Controller: `\application\controllers\c_dpr.php`
- Model: `\application\models\m_dpr.php`
- Logs: `\application\logs\log-YYYY-MM-DD.php`

### Key Function
`tampil_production_rev($tanggal_dari, $tanggal_sampai, $shift)`

### Session Variables
- `$_SESSION['nama_actor']` - User's full name
- `$_SESSION['posisi']` - User's position (kanit, kasi, etc.)
- `$_SESSION['divisi']` - User's division

## Next Steps

1. **Run the test script**: Access `http://192.168.1.201/dpr/test_export_query.php`
2. **Check the logs** after your next export
3. **Compare row counts** in both Excel files
4. **Report findings** with:
   - Log entries showing session and row count
   - Row counts from both Excel files
   - Whether you're logged in as kanit or not

## Important Notes

- **Both pages use the same function** - this is confirmed
- **The difference is in session-based filtering** - not in the query itself
- **If you're a kanit user, you WILL see fewer records** than a non-kanit user
- **This is by design** - kanit users should only see their own records
- **To export all data**, login as a user who is NOT in the 'kanit' position
