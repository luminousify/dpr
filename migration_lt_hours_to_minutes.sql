-- ====================================================================
-- Loss Time Migration: Hours to Minutes
-- Date: 2025-10-30
-- Description: Convert all Loss Time data from Hours (JAM) to Minutes (MENIT)
-- ====================================================================

-- Backup verification: Ensure backup exists before running
-- Affected Records:
--   - t_production_op_dl: ~1,012 records with type='LT'
--   - t_production_op: ~916 records with qty_lt > 0
--   - t_defectdanlosstime: All LT master records

-- ====================================================================
-- MIGRATION STEPS
-- ====================================================================

-- Step 1: Update transaction detail records (multiply hours by 60 to get minutes)
UPDATE t_production_op_dl 
SET qty = qty * 60 
WHERE type = 'LT';

-- Step 2: Update production summary records (multiply hours by 60 to get minutes)
UPDATE t_production_op 
SET qty_lt = qty_lt * 60;

-- Step 3: Update master data unit from JAM to MENIT
UPDATE t_defectdanlosstime 
SET satuan = 'MENIT' 
WHERE type = 'LT';

-- ====================================================================
-- VERIFICATION QUERIES (run after migration)
-- ====================================================================
-- SELECT COUNT(*) FROM t_production_op_dl WHERE type='LT' AND satuan='MENIT';
-- SELECT COUNT(*) FROM t_defectdanlosstime WHERE type='LT' AND satuan='MENIT';
-- SELECT id_production, qty, satuan FROM t_production_op_dl WHERE type='LT' ORDER BY id_production DESC LIMIT 10;
-- SELECT id_production, qty_lt FROM t_production_op WHERE qty_lt > 0 ORDER BY tanggal DESC LIMIT 10;
-- SELECT MAX(qty) as max_minutes FROM t_production_op_dl WHERE type='LT';
-- SELECT MAX(qty_lt) as max_minutes FROM t_production_op;

