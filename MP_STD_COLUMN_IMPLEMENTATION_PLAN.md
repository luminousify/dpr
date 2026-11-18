# MP Std Column Implementation Plan for c_machine/index

## Overview
This plan outlines the implementation of adding an "MP Std" column to the machine operation tables, displaying the `mp_standar` value from the `t_product` table.

## Database Analysis

### Table Relationships
- `operasi_mesin` table contains machine operation data with `kp_pr` field (product code)
- `t_product` table contains product data with `kode_product` field and `mp_standar` field
- Join condition: `operasi_mesin.kp_pr = t_product.kode_product`

### SQL Query for Testing
```sql
SELECT 
    om.*,
    tp.mp_standar AS mp_std
FROM operasi_mesin AS om
LEFT JOIN t_product AS tp ON om.kp_pr = tp.kode_product
WHERE om.tanggal >= '2025-01-01' 
  AND om.tanggal <= '2025-01-31' 
  AND om.shift = 1
ORDER BY om.line ASC, om.no_mesin ASC;
```

## Implementation Steps

### Step 1: Create Bash Script for SQL Testing
Create a standalone script to validate the SQL query using the MySQL client at:
`C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe`

### Step 2: Update Model Method
Modify the `tampil_machine_use` method in `application/models/m_machine.php` to include the JOIN with `t_product`

### Step 3: Update View Files
Modify all shift view files to add the "MP Std" column:
- `application/views/machine/shift1.php`
- `application/views/machine/shift2.php`
- `application/views/machine/shift3.php`

### Step 4: Testing
Verify the implementation works correctly across all shifts.

## Detailed Changes Required

### Model Changes (m_machine.php)
Current `tampil_machine_use` method needs to be updated to:
1. Add LEFT JOIN with t_product table
2. Select mp_standar field as mp_std
3. Maintain all existing functionality

### View Changes (shift1.php, shift2.php, shift3.php)
For each view file:
1. Add "MP Std" header after "Man Power" (line 14)
2. Add "MP Std" data cell after "Man Power" (line 62)
3. Update footer section to include new column (line 106)
4. Handle NULL values appropriately

## File Structure

### Files to Modify:
1. `test_mp_std_query.sh` - New bash script for SQL testing
2. `application/models/m_machine.php` - Update tampil_machine_use method
3. `application/views/machine/shift1.php` - Add MP Std column
4. `application/views/machine/shift2.php` - Add MP Std column
5. `application/views/machine/shift3.php` - Add MP Std column

## Expected Output
The "MP Std" column will display the manpower standard value for each product, showing values like:
- 0.5 (half person)
- 1.0 (one person)
- 1.5 (one and half persons)
- 2.0 (two persons)
- NULL or empty if no standard is set

## Testing Checklist
- [ ] SQL query executes without errors
- [ ] MP Std values display correctly in all shifts
- [ ] NULL values are handled appropriately
- [ ] Existing functionality remains intact
- [ ] Performance is acceptable