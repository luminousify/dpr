# MP Std Implementation Flowchart

## Process Flow

```mermaid
graph TD
    A[Start] --> B[Create Bash Script]
    B --> C[Test SQL Query]
    C --> D{Query Works?}
    D -->|No| E[Debug SQL]
    E --> C
    D -->|Yes| F[Update Model Method]
    F --> G[Modify shift1.php]
    G --> H[Modify shift2.php]
    H --> I[Modify shift3.php]
    I --> J[Test Implementation]
    J --> K{All Tests Pass?}
    K -->|No| L[Fix Issues]
    L --> J
    K -->|Yes| M[Complete]
```

## Database Schema Relationship

```mermaid
erDiagram
    operasi_mesin ||--o{ t_product : "kp_pr = kode_product"
    
    operasi_mesin {
        int id_machine_use
        date tanggal
        int shift
        string line
        string no_mesin
        string kp_pr
        string np_pr
        int man_power
        string operator
        string jenis_mesin
        string keterangan
        int running
        int send
    }
    
    t_product {
        int id_product
        string kode_product
        string nama_product
        decimal mp_standar
        string usage
        int cavity
        decimal sp
        string type
        decimal cyt_mc
        decimal cyt_quo
        string customer
        string divisi
        decimal cost
        int discontinue
    }
```

## Data Flow Diagram

```mermaid
graph LR
    A[operasi_mesin.kp_pr] -->|JOIN ON| B[t_product.kode_product]
    B --> C[t_product.mp_standar]
    C --> D[Alias as mp_std]
    D --> E[Display in View]
    
    F[Controller: c_machine/index] --> G[Model: m_machine/tampil_machine_use]
    G --> H[Database Query with JOIN]
    H --> I[Return Result Set]
    I --> J[View: shift1/2/3.php]
    J --> K[Display MP Std Column]
```

## Implementation Steps Detail

### Phase 1: SQL Validation
1. Create Bash script with MySQL client path
2. Test JOIN query between operasi_mesin and t_product
3. Verify mp_standar values are retrieved correctly
4. Confirm query performance is acceptable

### Phase 2: Backend Update
1. Modify `tampil_machine_use` method in `m_machine.php`
2. Add LEFT JOIN with t_product table
3. Select mp_standar field with alias
4. Maintain all existing functionality

### Phase 3: Frontend Update
1. Update shift1.php view file
2. Update shift2.php view file
3. Update shift3.php view file
4. Add column headers and data cells
5. Handle NULL values appropriately

### Phase 4: Testing
1. Test all three shift views
2. Verify data display accuracy
3. Check for PHP errors
4. Validate JavaScript functionality
5. Confirm responsive design works

## Key Technical Considerations

### Database Considerations
- Use LEFT JOIN to ensure all machine records appear
- Alias mp_standar as mp_std for consistency
- Handle NULL values gracefully
- Maintain query performance

### PHP Considerations
- Use isset() to check for NULL values
- Preserve existing array structure
- Maintain backward compatibility
- Follow CodeIgniter patterns

### Frontend Considerations
- Maintain table responsiveness
- Preserve existing styling
- Add proper column alignment
- Handle empty cells gracefully

## Risk Mitigation

### Potential Issues
1. **Query Performance**: JOIN might slow down large result sets
   - Mitigation: Test with realistic data volumes
   
2. **NULL Values**: Products without mp_standar might cause errors
   - Mitigation: Use isset() checks in views
   
3. **Breaking Changes**: Modifying core method might affect other functionality
   - Mitigation: Preserve all existing SELECT fields and conditions

### Rollback Plan
1. Keep backup of original m_machine.php
2. Version control for view files
3. Database backup before implementation
4. Clear rollback procedure documented

## Success Criteria

✅ SQL query executes without errors  
✅ MP Std values display correctly in all shifts  
✅ NULL values handled appropriately  
✅ Existing functionality preserved  
✅ No performance degradation  
✅ Responsive design maintained  
✅ No PHP or JavaScript errors