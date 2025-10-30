# Nett and Gross Production Calculation Formulas

## Overview
This document outlines the formulas used to calculate `nett_prod` and `gross_prod` values that are saved to the `t_production_op` table in the DPR (Daily Production Report) system.

## Variables Used
- `qty` = Quantity OK produced (user input field `#qty`)
- `defect` = NG (Not Good) quantity (user input field `#amountNG`)
- `kalkulasi` = `qty + defect` (Total production including defects)
- `ct_aktual` = Actual cycle time in seconds (user input field `#ct_mc_aktual`)
- `cavity` = Number of cavities in mold (from product master data via autocomplete)
- `cavity2` = Secondary cavity value (from product master data via autocomplete)
- `nwt` = Normal work time in hours (field `#nwt`)
- `ot` = Overtime in hours (field `#ot_mp`)
- `LT` = Loss time in minutes (field `#amountLT`)
- `hasil_time` = Production time calculation result (theoretical time needed to produce the parts)
- `WorkHour` = Effective working hours (calculated: `hasil + LT - ot`) (actual time available for production)

## Difference Between hasil_time and WorkHour

### hasil_time (Theoretical Production Time)
- **Purpose**: Calculates the theoretical time required to produce the total parts (qty + defect)
- **Formula**: `(kalkulasi / cavity) * (ct_aktual / 3600)`
- **Unit**: Hours
- **Represents**: The ideal time needed to complete production based on cycle time and cavities
- **Example**: If producing 1000 parts with 4 cavities at 17 seconds cycle time:
  - hasil_time = (1000 / 4) * (17 / 3600) = 250 * 0.004722 = 1.18 hours

### WorkHour (Actual Available Working Time)
- **Purpose**: The actual time available for production during the shift
- **Formula**: `hasil + (LT / 60) - ot` 
- **Where**: 
  - `hasil` = hasil_time (rounded to 1 decimal)
  - `LT` = Loss time **stored in MINUTES** in database, **converted to hours** for calculations
  - `LT / 60` = Conversion from minutes to hours
  - `ot` = Overtime in hours
- **Unit**: Hours
- **Represents**: The actual production window after accounting for losses and overtime
- **Example**: If hasil_time = 1.18 hours, LT = 30 minutes (0.5 hours), ot = 0 hours:
  - WorkHour = 1.18 + (30/60) - 0 = 1.18 + 0.5 = 1.68 hours

### Key Difference
- **hasil_time** = How long it SHOULD take to produce the parts (based on cycle time)
- **WorkHour** = How much time WAS available for production (shift duration - losses + overtime)

This distinction is crucial because:
- Production efficiency is calculated by comparing actual output against available time (WorkHour)
- Theoretical capacity is based on cycle time calculations (hasil_time)
- The difference between them indicates whether production was faster or slower than expected
- `Overtime` = Overtime hours (from `#ot_mp`)
- `TotStopTime` = Total stop time/loss time (LT converted to hours)
- `OK` = Quantity of good products (same as `qty`)
- `ProductCavity` = Cavity value for production (same as `cavity2`)

## Key Calculations

### 1. Production Time Calculation
```javascript
hasil_time = (kalkulasi / cavity) * (ct_aktual / 3600)
```

#### Variable Sources:
- **`kalkulasi`**: `qty + defect` (Total production including defects)
  - `qty` comes from user input field `#qty` (Quantity OK produced)
  - `defect` comes from user input field `#amountNG` (NG quantity)
- **`cavity`**: Number of cavities in mold
  - Retrieved from database when product is selected via autocomplete
  - Populated in input field `#cavity` from product master data
- **`ct_aktual`**: Actual cycle time (in seconds)
  - User input field `#ct_mc_aktual` entered by operator
  - Value is typed in seconds (e.g., 17 seconds = 17)

### 2. Gross Production Formula

#### Method 1: With losses considered
```javascript
raw_nilaiGross = 3600 * (nwt_new - calDT_new_lagi) / (qty * cavity)
```

#### Method 2: Without losses  
```javascript
raw_nilaiGross_2 = 3600 * nwt_new / (qty * cavity2)
```

#### Final selection logic:
```javascript
if (nwt_new == 8) {
    if (hasil > 8) use nilaiGross_2 else use nilaiGross
} else {
    if (hasil > 5) use nilaiGross_2 else use nilaiGross
}
```

### 3. Nett Production Formula

#### Traditional method:
```javascript
if (defect != 0) {
    nilaiNett = nilaiGross;
} else {
    nilaiNett = (hasil * 3600 / kalkulasi) * cavity2;
}
```

### 4. Alternative/Newer Implementation Formulas

#### Gross Production (Alternative)
```javascript
grossProduction = 3600 / ((OK / ProductCavity) / (WorkHour + Overtime))
```

#### Nett Production (Alternative)
```javascript  
NProd = 3600 / ((OK / ProductCavity) / (WorkHour + Overtime - TotStopTime))
```

## Summary

- **Gross Production** measures the theoretical maximum production rate without considering losses
- **Nett Production** measures the actual production rate accounting for all losses and downtime
- Both values are calculated in **shots per hour** and represent the production efficiency
- The formulas use **3600 seconds** to convert between hours and seconds in the calculations
- Values are rounded using a custom rounding function before being saved to the database

## Code Source
These formulas are implemented in the JavaScript function `GrossNett()` located in:
`application/views/online/input_dpr.php`

The calculated values are then processed in the PHP controllers:
- `application/controllers/c_operator.php`
- `application/controllers/c_dpr.php` 
- `application/controllers/c_new.php`

And saved to the `t_production_op` table fields:
- `nett_prod` (float, nullable)
- `gross_prod` (float, nullable)

## Database View Usage
The values are aggregated in the MySQL view `productivity_by_day` and used to calculate productivity percentages:

- **Nett Percentage**: `(SUM(ct_standar) / SUM(nett_prod)) * 100`
- **Gross Percentage**: `(SUM(ct_standar) / SUM(gross_prod)) * 100`

These percentages are displayed on the dashboard homepage at `/c_new/home`.

## Migration History

### Loss Time Format Migration (October 30, 2025)

**Background**: Loss Time data format was migrated from **Hours (Jam)** to **Minutes (Menit)** to improve data entry accuracy and align with operational practices.

**Changes Made**:
1. **Database Migration**:
   - All existing Loss Time values in `t_production_op_dl.qty` (type='LT') multiplied by 60
   - All existing Loss Time totals in `t_production_op.qty_lt` multiplied by 60
   - Master data `t_defectdanlosstime.satuan` changed from 'JAM' to 'MENIT' for all LT types
   - Transaction data `t_production_op_dl.satuan` changed from 'JAM' to 'MENIT' for all LT records

2. **Code Updates**:
   - JavaScript calculations updated to convert minutes to hours: `LT_new = LT / 60`
   - All UI labels changed from "Jam" to "Menit"
   - Report query aliases updated from `total_hours` to `total_minutes`
   - CDT (Change Down Time) calculations updated to handle minutes

3. **Important Notes**:
   - **User Input**: Users now enter Loss Time in **MINUTES**
   - **Database Storage**: Loss Time is stored as **MINUTES** in all tables
   - **Calculations**: JavaScript/PHP automatically converts minutes to hours where needed for time-based calculations
   - **Reports**: All Loss Time reports now display values in **MINUTES**
   - **Formula Compatibility**: All existing formulas remain functionally the same, with automatic unit conversion applied

**Affected Tables**:
- `t_production_op` (qty_lt field)
- `t_production_op_dl` (qty and satuan fields where type='LT')
- `t_defectdanlosstime` (satuan field where type='LT')

**Affected Files**:
- `assets/scripts/input_dpr.js` (GrossNett function)
- `application/views/dpr/add_dpr.php` (UI labels and calculations)
- `application/views/dpr/edit_dpr.php` (UI labels and calculations)
- `application/views/dpr/view_dpr.php` (UI labels and CDT calculation)
- `application/views/dpr/dpr.php` (CDT calculation)
- `application/models/m_report.php` (query aliases)

**Rollback**: If needed, rollback SQL script available at `migration_lt_hours_to_minutes.sql` (reverse operation: divide by 60)
