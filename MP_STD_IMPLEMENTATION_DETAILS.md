# MP Std Column Implementation Details

## Bash Script for SQL Testing

Create a file named `test_mp_std_query.sh` with the following content:

```bash
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
```

## Model Changes (m_machine.php)

### Current tampil_machine_use Method (Lines 11-25)
```php
public function tampil_machine_use($table , $tanggal_dari , $tanggal_sampai,$shift,$line)
{
    if($line == 'All') { $lineNya = ''; } else { $lineNya = $this->db->where('line', $line);}
    $this->db->select('*');
    $this->db->from($table);
    // $this->db->join('t_production_op', 't_production_op.id = blogs.id');
    //$this->db->join('t_production_op as w', 'q.tanggal = w.tanggal','q.shift = w.shift','q.no_mesin = w.mesin', 'left');
    $this->db->where('tanggal >=', $tanggal_dari); 
    $this->db->where('tanggal <=', $tanggal_sampai); 
    $this->db->where('shift', $shift); 
    $lineNya;
    $this->db->order_by('line','ASC');
    $this->db->order_by('no_mesin','ASC');
    return $this->db->get();         
}
```

### Updated tampil_machine_use Method
```php
public function tampil_machine_use($table , $tanggal_dari , $tanggal_sampai,$shift,$line)
{
    if($line == 'All') { $lineNya = ''; } else { $lineNya = $this->db->where('line', $line);}
    $this->db->select('om.*, tp.mp_standar AS mp_std');
    $this->db->from($table . ' AS om');
    $this->db->join('t_product AS tp', 'om.kp_pr = tp.kode_product', 'left');
    $this->db->where('om.tanggal >=', $tanggal_dari); 
    $this->db->where('om.tanggal <=', $tanggal_sampai); 
    $this->db->where('om.shift', $shift); 
    $lineNya;
    $this->db->order_by('om.line','ASC');
    $this->db->order_by('om.no_mesin','ASC');
    return $this->db->get();         
}
```

## View Changes

### shift1.php Changes

#### Header Section (After Line 14)
Add new header after "Man Power":
```html
<th>Man Power</th>
<th>MP Std</th>
```

#### Data Section (After Line 62)
Add new data cell after "Man Power":
```html
<td><?= $data['man_power']; ?></td>
<td><?= isset($data['mp_std']) ? $data['mp_std'] : ''; ?></td>
```

#### Footer Section (After Line 106)
Add new header in footer:
```html
<th>Man Power</th>
<th>MP Std</th>
```

### shift2.php Changes

#### Header Section (After Line 14)
```html
<th>Man Power</th>
<th>MP Std</th>
```

#### Data Section (After Line 61)
```html
<td><?= $data['man_power']; ?></td>
<td><?= isset($data['mp_std']) ? $data['mp_std'] : ''; ?></td>
```

#### Footer Section (After Line 96)
```html
<th>Man Power</th>
<th>MP Std</th>
```

### shift3.php Changes

#### Header Section (After Line 14)
```html
<th>Man Power</th>
<th>MP Std</th>
```

#### Data Section (After Line 61)
```html
<td><?= $data['man_power']; ?></td>
<td><?= isset($data['mp_std']) ? $data['mp_std'] : ''; ?></td>
```

#### Footer Section (After Line 96)
```html
<th>Man Power</th>
<th>MP Std</th>
```

## Implementation Sequence

1. **Create and test the Bash script** to validate SQL query
2. **Update the model** to include JOIN with t_product
3. **Modify shift1.php** to add MP Std column
4. **Modify shift2.php** to add MP Std column
5. **Modify shift3.php** to add MP Std column
6. **Test the implementation** across all shifts

## Testing Checklist

- [ ] Bash script executes without errors
- [ ] SQL query returns expected results with mp_std values
- [ ] Model method updated correctly
- [ ] MP Std column appears in all shift tables
- [ ] Data displays correctly (including NULL values)
- [ ] Existing functionality remains intact
- [ ] No PHP errors in logs
- [ ] No JavaScript errors in browser

## Expected Results

The "MP Std" column will display:
- Values like 0.5, 1.0, 1.5, 2.0 for products with standards
- Empty cells for products without standards (NULL values)
- Proper alignment with existing "Man Power" column

## Notes

1. The JOIN uses LEFT JOIN to ensure all machine records are displayed even if no matching product is found
2. NULL mp_standar values are handled with isset() check to avoid PHP notices
3. The column alias "mp_std" is used for consistency in the view files
4. All existing functionality is preserved while adding the new column