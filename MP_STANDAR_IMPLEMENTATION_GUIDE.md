# MP Standar Implementation Guide

## Overview
This guide provides step-by-step instructions to add the `mp_standar` (manpower standard) column to the `t_product` table and migrate data from Excel file.

## Prerequisites
- Database access to `dpr_injection_new` on server `192.168.1.202`
- Excel file: `C:\Users\Pratama\AppData\Roaming\Output Messenger\CDJAAA\Received Files\202510\MP Actual.xls`
- PHP with PhpSpreadsheet library installed
- MySQL client access

## Files Created
1. **add_mp_standar_column.sql** - Database schema update script
2. **migrate_mp_standar.php** - Data migration script from Excel to database
3. **rollback_mp_standar.sql** - Rollback script if needed
4. **application/models/m_inventory.php** - Updated model with new functions

## Implementation Steps

### Step 1: Database Schema Update
Execute the SQL script to add the new column:

```bash
"C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe" -h 192.168.1.202 -u endang -pendangthea0 -D dpr_injection_new < add_mp_standar_column.sql
```

### Step 2: Data Migration
Run the PHP migration script:

```bash
php migrate_mp_standar.php
```

This script will:
- Read Excel file with columns: `product_code`, `mp_std`
- Match `product_code` with `kode_product` in database
- Update `mp_standar` field with validated decimal values (0.5, 1.0, etc.)
- Provide detailed progress output

### Step 3: Verify Results
Check the migration results:

```sql
SELECT kode_product, nama_product, mp_standar 
FROM t_product 
WHERE mp_standar IS NOT NULL 
ORDER BY kode_product 
LIMIT 10;
```

### Step 4: Application Integration
The updated model includes new functions:

- `update_mp_standar($id_product, $mp_standar)` - Update individual product
- `get_products_with_mp_standar()` - Get all products with mp_standar values

### Step 5: Testing
Test the implementation:
1. Verify data integrity after migration
2. Test CRUD operations with new column
3. Check that existing functionality still works
4. Validate Excel data matches database updates

## Excel File Structure
- **File**: `MP Actual.xls`
- **Columns**: 
  - Column A: `product_code` (matches `kode_product` in database)
  - Column B: `mp_std` (decimal values like 0.5, 1.0)
- **Data Validation**: Only accepts values 0.5, 1.0, 1.5, 2.0, etc.

## Database Column Details
- **Name**: `mp_standar`
- **Type**: `DECIMAL(3,1)`
- **Nullable**: YES
- **Default**: NULL
- **Position**: After `mtrl_use_kg`
- **Purpose**: Store manpower standard per product for production planning

## Rollback Plan
If issues occur, use the rollback script:

```bash
"C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe" -h 192.168.1.202 -u endang -pendangthea0 -D dpr_injection_new < rollback_mp_standar.sql
```

## Validation Checks
After migration, verify:
1. **Count**: Number of products with mp_standar values
2. **Data Integrity**: Valid decimal values only
3. **Matching**: Excel data correctly mapped to database products
4. **Performance**: No impact on existing queries

## Troubleshooting

### Common Issues
1. **Excel file not found**: Check file path and permissions
2. **Connection failed**: Verify database credentials and network access
3. **Invalid data**: Check Excel format and decimal values
4. **PhpSpreadsheet not found**: Install via Composer: `composer require phpoffice/phpspreadsheet`

### Error Messages
- "Excel file not found": Check file path
- "Connection failed": Verify database credentials
- "Invalid mp_std value": Check Excel data format
- "Prepare failed": Database connection or permissions issue

## Success Criteria
✅ Column added successfully to t_product table  
✅ Excel data migrated without errors  
✅ All mp_standar values are valid decimals  
✅ Existing application functionality unaffected  
✅ New model functions working correctly  

## Support
For issues during implementation:
1. Check MySQL error logs
2. Verify PHP error reporting
3. Test with sample data first
4. Create database backup before starting

## Next Steps
After successful implementation:
1. Update views to display mp_standar data
2. Add forms for editing mp_standar values
3. Include mp_standar in production planning reports
4. Add validation in data entry forms