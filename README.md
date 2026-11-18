# Data Reconciliation Script

## Overview

This Python script performs rigorous data reconciliation between a MySQL database and an Excel file, identifying all discrepancies between the two data sources. It compares product data using SKU as the common identifier and generates a detailed report of any mismatches.

## Features

- Connects to MySQL database and fetches data from `t_product` table
- Reads data from Excel file with product information
- Performs data comparison using SKU as the key identifier
- Compares `mp_std` values from database with 'Actual MP' values from Excel
- Generates comprehensive reconciliation report with four sections:
  1. Summary statistics
  2. Detailed list of mismatched values
  3. SKUs found only in database
  4. SKUs found only in Excel file
- Robust error handling for database and file operations
- Detailed logging throughout the process

## Requirements

- Python 3.6 or higher
- Required Python packages (install via pip):
  ```
  pip install -r requirements.txt
  ```

## Configuration

Before running the script, you need to update the database connection parameters in the [`data_reconciliation.py`](data_reconciliation.py:25) file:

```python
DB_CONFIG = {
    'host': 'YOUR_DB_HOST',        # Update with your database host
    'user': 'YOUR_DB_USER',        # Update with your database username
    'password': 'YOUR_DB_PASSWORD', # Update with your database password
    'database': 'YOUR_DATABASE_NAME' # Update with your database name
}
```

## File Locations

The script expects the Excel file to be located at:
```
C:\Users\Pratama\AppData\Roaming\Output Messenger\CDJAAA\Received Files\202510\MP Actual.xls
```

The reconciliation report will be saved as `reconciliation_report.txt` in the same directory as the script.

## Data Mapping

### Database Table: `t_product`
- Key Column: `product_sku`
- Value Column: `mp_std`

### Excel File
- Worksheet: First worksheet (index 0)
- Key Column: `SKU`
- Value Column: `Actual MP`

## Usage

1. Install the required dependencies:
   ```bash
   pip install -r requirements.txt
   ```

2. Update the database configuration in the script

3. Run the script:
   ```bash
   python data_reconciliation.py
   ```

4. Check the console output for progress and any errors

5. Review the generated `reconciliation_report.txt` file for detailed results

## Output Report Structure

The generated report contains four distinct sections:

### Section 1: Summary
- Total number of matched records
- Total number of mismatched values
- Number of records only in database
- Number of records only in Excel file

### Section 2: Mismatched Values
- Detailed list of all records where `mp_std` and 'Actual MP' values differ
- Shows SKU, database value, and Excel value for each mismatch

### Section 3: SKUs Found Only in Database
- List of SKUs that exist in the database but not in the Excel file

### Section 4: SKUs Found Only in Excel
- List of SKUs that exist in the Excel file but not in the database

## Error Handling

The script includes comprehensive error handling for:
- Database connection failures
- SQL query errors
- Excel file access issues
- Data processing errors
- File writing errors

All errors are logged to the console with detailed error messages.

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Verify database credentials are correct
   - Check if database server is accessible
   - Ensure network connectivity

2. **Excel File Not Found**
   - Verify the Excel file path is correct
   - Check if the file exists and is accessible
   - Ensure file is not locked by another application

3. **Column Not Found Errors**
   - Verify column names in database table and Excel file
   - Check for extra spaces or special characters in column names
   - Ensure the correct worksheet is being accessed

4. **Permission Errors**
   - Ensure script has read access to Excel file
   - Ensure script has write access to output directory

## Script Architecture

The script is modular with the following main functions:

- [`connect_to_database()`](data_reconciliation.py:44): Establishes MySQL connection
- [`fetch_database_data()`](data_reconciliation.py:67): Retrieves data from database
- [`read_excel_data()`](data_reconciliation.py:103): Reads Excel file
- [`clean_and_prepare_data()`](data_reconciliation.py:140): Cleans and prepares data
- [`compare_data()`](data_reconciliation.py:170): Performs data comparison
- [`generate_report()`](data_reconciliation.py:220): Creates reconciliation report
- [`save_report_to_file()`](data_reconciliation.py:290): Saves report to file
- [`main()`](data_reconciliation.py:312): Orchestrates the entire process

## Dependencies

- **pandas**: Data manipulation and analysis
- **mysql-connector-python**: MySQL database connectivity
- **openpyxl**: Excel file reading support

## License

This script is provided as-is for data reconciliation purposes.