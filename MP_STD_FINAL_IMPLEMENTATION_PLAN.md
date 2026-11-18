# MP Std Column Final Implementation Plan

## Executive Summary
This document provides a complete implementation plan for adding an "MP Std" column to the c_machine/index page, displaying manpower standard values from the `t_product.mp_standar` field.

## Key Findings

### Database Relationship
- **Primary Table**: `operasi_mesin` (machine operation data)
- **Reference Table**: `t_product` (product master data)
- **Join Field**: `operasi_mesin.kp_pr = t_product.kode_product`
- **Target Field**: `t_product.mp_standar` (manpower standard values)

### Current Table Structure
The machine operation tables (shift1, shift2, shift3) currently display:
- Run, Line, No. MC, Ton, Kode Product, Nama Product
- CT STD, CT Run, Cavity, Operator, **Man Power**
- Auto/Semi, Ket., Tanggal, Group, Shift, Run, Send, Edit, Delete

## Implementation Plan

### Phase 1: SQL Query Validation
**File**: `test_mp_std_query.sh`
**Purpose**: Validate the JOIN query before implementation

```bash
#!/bin/bash
# MP Std Query Test Script
MYSQL_CLIENT="C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysql.exe"
DB_HOST="192.168.1.202"
DB_USER="endang"
DB_PASS="pendangthea0"
DB_NAME="dpr_injection_new"

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
LIMIT 5;"

"$MYSQL_CLIENT" -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" -D "$DB_NAME" -e "$SQL_QUERY"
```

### Phase 2: Backend Implementation
**File**: `application/models/m_machine.php`
**Method**: `tampil_machine_use()`

**Current Code**:
```php
$this->db->select('*');
$this->db->from($table);
```

**Updated Code**:
```php
$this->db->select('om.*, tp.mp_standar AS mp_std');
$this->db->from($table . ' AS om');
$this->db->join('t_product AS tp', 'om.kp_pr = tp.kode_product', 'left');
```

### Phase 3: Frontend Implementation

#### shift1.php Changes
**Header Addition** (after line 14):
```html
<th>Man Power</th>
<th>MP Std</th>
```

**Data Cell Addition** (after line 62):
```html
<td><?= $data['man_power']; ?></td>
<td><?= isset($data['mp_std']) ? $data['mp_std'] : ''; ?></td>
```

**Footer Addition** (after line 106):
```html
<th>Man Power</th>
<th>MP Std</th>
```

#### shift2.php Changes
Same pattern as shift1.php, but using `$data_tabel2` variable

#### shift3.php Changes
Same pattern as shift1.php, but using `$data_tabel3` variable

## Technical Specifications

### Data Display Rules
- **Non-NULL values**: Display as decimal (e.g., 0.5, 1.0, 1.5, 2.0)
- **NULL values**: Display as empty string
- **Alignment**: Center-aligned with other numeric columns
- **Styling**: Consistent with existing table design

### Performance Considerations
- LEFT JOIN ensures all machine records appear regardless of product match
- Query maintains existing WHERE conditions and ORDER BY clauses
- No additional indexes required (kode_product should already be indexed)

### Error Handling
- PHP `isset()` checks prevent undefined index notices
- NULL values handled gracefully in display
- Existing error handling preserved

## Testing Strategy

### Unit Testing
1. **SQL Query Test**: Verify JOIN returns correct mp_std values
2. **Model Method Test**: Confirm modified method returns expected structure
3. **View Rendering Test**: Check all three shift views display correctly

### Integration Testing
1. **End-to-End Flow**: Test from controller to view
2. **Data Consistency**: Verify same data across all shifts
3. **Functionality Preservation**: Ensure existing features work

### User Acceptance Testing
1. **Visual Verification**: Column appears in correct position
2. **Data Accuracy**: Values match product standards
3. **Responsive Design**: Table works on different screen sizes

## Risk Assessment

### High Risk
- **Query Performance**: Large datasets might slow down
  - **Mitigation**: Monitor query execution time

### Medium Risk
- **NULL Value Handling**: Potential PHP errors
  - **Mitigation**: Use isset() checks

### Low Risk
- **Visual Layout**: Column alignment issues
  - **Mitigation**: Follow existing CSS patterns

## Deployment Plan

### Pre-Deployment
1. **Database Backup**: Full backup of dpr_injection_new
2. **Code Backup**: Version control of all modified files
3. **Staging Test**: Complete testing on staging server

### Deployment Steps
1. **Deploy Model Changes**: Update m_machine.php
2. **Deploy View Changes**: Update all three shift files
3. **Clear Cache**: Clear application cache if applicable
4. **Smoke Test**: Basic functionality verification

### Post-Deployment
1. **Monitor Performance**: Check query execution times
2. **Error Monitoring**: Review PHP error logs
3. **User Feedback**: Collect user responses

## Success Metrics

### Technical Metrics
- Query execution time < 2 seconds
- Zero PHP errors
- Zero JavaScript errors
- Responsive design maintained

### Business Metrics
- MP Std values display correctly
- User adoption positive
- No workflow disruption
- Data accuracy maintained

## Rollback Plan

### Immediate Rollback
1. Restore original m_machine.php from backup
2. Restore original view files from backup
3. Clear application cache

### Full Rollback
1. Restore database from pre-deployment backup
2. Restore all modified files
3. Verify system functionality

## Conclusion

This implementation plan provides a comprehensive approach to adding the "MP Std" column to the machine operation tables. The plan addresses technical requirements, performance considerations, testing strategies, and risk mitigation to ensure a successful deployment.

The implementation will:
- Display manpower standards from t_product table
- Maintain all existing functionality
- Handle edge cases gracefully
- Provide a seamless user experience

Next step is to switch to Code mode to implement these changes.