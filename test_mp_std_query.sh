#!/bin/bash

# MP Std Query Test Script
# This script tests the SQL query to join operasi_mesin with t_product

echo "Testing MP Std SQL Query..."
echo "================================"

# MySQL Client Path
MYSQL_CLIENT="C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe"

# Database Connection Parameters
DB_HOST="192.168.1.202"
DB_USER="endang"
DB_PASS="pendangthea0"
DB_NAME="dpr_injection_new"

# Test Query
SQL_QUERY="
SELECT 
    om.*,
    tp.mp_standar AS mp_std
FROM operasi_mesin AS om
LEFT JOIN t_product AS tp ON om.kp_pr = tp.kode_product
WHERE om.tanggal >= '2025-01-01' 
  AND om.tanggal <= '2025-01-31' 
  AND om.shift = 1
ORDER BY om.line ASC, om.no_mesin ASC
LIMIT 5;
"

echo "Executing SQL Query..."
echo "$MYSQL_CLIENT" -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" -D "$DB_NAME" -e "$SQL_QUERY"

# Execute the query
"$MYSQL_CLIENT" -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" -D "$DB_NAME" -e "$SQL_QUERY"

echo ""
echo "Query test completed."
echo "================================"