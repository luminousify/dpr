# DPR CSV Import System - User Guide

## Overview

The DPR CSV Import System allows you to import production data from CSV files into the database. The system handles:

- **Main production data** → `t_production_op` table
- **NG defects** → `t_production_op_dl` table (type='NG')
- **Loss time records** → `t_production_op_dl` table (type='LT')
- **Cutting tools usage** → `t_production_op_cutting_tools_usage` table

## Access the Import System

Navigate to:
```
http://server64/dpr/c_import_csv
```

## Step-by-Step Import Process

### 1. Prepare Your CSV File

Ensure your CSV file meets these requirements:

**File Format:**
- Extension: `.csv`
- Delimiter: Comma (`,`)
- Encoding: UTF-8
- Maximum size: 10MB

**Decimal Format:**
- Use comma as decimal separator: `24,5` (not `24.5`)
- Example: `1,45` = 1.45

**Date Format:**
- Use YYYY-MM-DD format
- Example: `2026-01-02`

**Product ID Format:**
- Can include dots: `189.674.500`
- System will automatically convert to: `189674500`

### 2. Upload CSV File

1. Go to the import page: `http://server64/dpr/c_import_csv`
2. Click "Choose File" or drag & drop your CSV file
3. Click "Upload & Preview"

### 3. Preview Data

The system will show:
- First 10 rows of your data
- Total number of rows in the file
- Key fields for verification

**Review Checklist:**
- ✓ Tanggal (Date) is correct
- ✓ Product ID matches
- ✓ OK quantity is correct
- ✓ NG quantity is correct
- ✓ Operator and Kanit names are correct

### 4. Start Import

1. Click "Start Import Process"
2. Wait for the import to complete (approximately 0.5 seconds per row)
3. Review the import results

### 5. Review Results

The result page shows:
- **Total Rows**: Number of rows processed
- **Successful**: Number of rows imported correctly
- **Failed**: Number of rows that failed to import
- **Success Rate**: Percentage of successful imports
- **Error Log**: Detailed error messages for failed rows

## CSV Column Mapping

### Required Columns

| CSV Column | Database Field | Format | Notes |
|------------|---------------|---------|-------|
| Tanggal | tanggal | YYYY-MM-DD | Production date |
| Mesin | mesin | Integer | Machine number |
| Shift | shift | 1, 2, or 3 | Shift number |
| Group Shift | group | A, B, or C | Group identifier |
| Product ID | id_bom | String | Product code (dots allowed) |
| Operator | operator | String | Operator name |
| Kanit | kanit | String | Supervisor name |
| OK | qty_ok | Integer | Quantity OK |

### Optional Columns

| CSV Column | Database Field | Format | Notes |
|------------|---------------|---------|-------|
| W H | nwt_mp | Decimal (comma) | Normal working time |
| O T | ot_mp | Decimal (comma) | Overtime |
| Total LT | qty_lt | Decimal (comma) | Total loss time |
| Tool | tooling | Integer | Tooling number |
| Target Qty | target_mc | Integer | Target quantity |
| Total Defect | qty_ng | Integer | Total NG quantity |
| CT Std | ct_mc | Decimal (comma) | Cycle time standard |
| CT Actual | ct_mc_aktual | Decimal (comma) | Actual cycle time |
| N-Prod | nett_prod | Decimal (comma) | Net productivity |
| G-Prod | gross_prod | Decimal (comma) | Gross productivity |
| Runner | runner | Decimal (comma) | Runner weight |
| Loss Purging Matl | loss_purge | Decimal (comma) | Loss purge |
| Matl Lot No | lot_material_no | String | Material lot number |
| Customer | customer | String | Customer name |
| Keterangan | keterangan | String | Remarks |
| Cutting Tool | cutting_tools_id | String | Tool codes (comma-separated) |
| Verif. Kanit | cek_kanit | V or empty | Verification flag |
| Verif. Kasi | cek_kasi | V or empty | Verification flag |

### NG Defect Columns

All columns starting with `NG-`:
- NG-Bending
- NG-Berawan
- NG-Blackdot
- NG-Broken
- NG-Crack
- NG-Dent
- NG-Dirty
- NG-Discolour
- NG-Ejector Mark
- NG-Flash
- NG-Flow Gate
- NG-Flow Mark
- NG-Foreign Material
- NG-Gas Burn
- NG-Gas Mark
- NG-Gate Bolong
- NG-Gate Long
- NG-Hangus
- NG-Hike
- NG-Oil
- NG-Oversize
- NG-Pin Plong
- NG-Pin Seret
- NG-Scratch
- NG-Settingan
- NG-Short Shoot
- NG-Silver
- NG-Sink Mark
- NG-Undercut
- NG-Undersize
- NG-Void
- NG-Waving
- NG-Weld Line
- NG-White Dot
- NG-White Mark
- NG Start Up

### LT Loss Time Columns

All columns starting with `LT-`:
- LT-Adjust Parameter
- LT-Pre Heating Material
- LT-Cleaning Hopper & Barrel
- LT-Set Up Mold
- LT-Set Up Parameter Machine
- LT-IPQC Inspection
- LT-No Packing
- LT-No Material
- LT-Material Problem
- LT-No Operator
- LT-Daily Checklist
- LT-Overhoule Mold
- LT-Mold Problem
- LT-Trial
- LT-Machine
- LT-Hopper Dryer
- LT-Robot
- LT-MTC
- LT-Cooling Tower
- LT-Compressor
- LT-Listrik
- LT-QC Lolos
- Set Up Awal
- Trial

## Data Validation

The system validates:

1. **Required fields**: tanggal, shift, mesin, operator, kanit
2. **Product ID**: Must exist in `select_bom` table
3. **Operator**: Must exist in `t_operator` table
4. **Kanit**: Must exist in `t_operator` table
5. **Duplicate check**: `id_production` must be unique

## Error Handling

If a row fails to import:
- The entire transaction is rolled back
- Error is logged with row number and reason
- Import continues with next row

Common errors:
- **Product ID not found**: Check if product exists in `select_bom` table
- **Invalid date format**: Use YYYY-MM-DD format
- **Missing required field**: Ensure all required columns are filled
- **Duplicate entry**: Data with same `id_production` already exists

## Import Performance

- **Estimated time**: ~0.5 seconds per row
- **120 rows**: ~1 minute
- **1000 rows**: ~8 minutes

## View Imported Data

After successful import, view your data at:
```
http://server64/dpr/c_dpr/dpr
```

## Troubleshooting

### Issue: Product ID not found
**Solution:**
1. Check if Product ID exists in `select_bom` table
2. Remove dots from Product ID in CSV
3. Example: Change `189.674.500` to `189674500`

### Issue: Import fails silently
**Solution:**
1. Check PHP error logs: `application/logs/`
2. Check file permissions on `uploads/` and `backups/` directories
3. Ensure database connection is working

### Issue: Decimal values are incorrect
**Solution:**
1. Use comma as decimal separator: `24,5` not `24.5`
2. Do not use thousand separators
3. Empty values are treated as 0

### Issue: NG/LT values not importing
**Solution:**
1. Ensure column names start with `NG-` or `LT-`
2. Values must be greater than 0 to be imported
3. Check for extra spaces in column names

## Support

For issues or questions:
1. Check the error log on the result page
2. Review this documentation
3. Contact system administrator

## File Locations

- **Import controller**: `application/controllers/c_import_csv.php`
- **Import model**: `application/models/m_import_csv.php`
- **View files**: `application/views/import_csv/`
- **Upload directory**: `uploads/`
- **Backup directory**: `backups/`
- **Test script**: `test_csv_import.php`

## Security Notes

- CSV files are deleted after processing
- Database backups are created before import
- All data is validated before insertion
- Transactions ensure data integrity
