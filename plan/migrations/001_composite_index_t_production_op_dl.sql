-- Ticket 1 / Phase 0.3: Composite index on t_production_op_dl
--
-- Purpose: speeds up the pivot aggregation in tampil_production_rev()
-- which groups t_production_op_dl rows by (id_production, nama).
--
-- Safe / idempotent: guarded by an INFORMATION_SCHEMA check so the script
-- can be re-run without error on any environment (dev or prod).
-- Compatible with MySQL 5.0+ and MariaDB (uses PREPARE/EXECUTE on a
-- dynamically-built statement; no SET STATEMENT or version check needed).

-- Idempotent on MySQL 5.0+ and MariaDB:
SET @create := (
  SELECT IFNULL(
    (SELECT CONCAT(
       'CREATE INDEX idx_idprod_nama ON t_production_op_dl (id_production, nama)'
     )
     FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME   = 't_production_op_dl'
       AND INDEX_NAME   = 'idx_idprod_nama'
       HAVING COUNT(*) = 0
    ),
    'SELECT "idx_idprod_nama already exists, skipping" AS msg'
  )
);
PREPARE stmt FROM @create;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verify:
-- SHOW INDEX FROM t_production_op_dl WHERE Key_name = 'idx_idprod_nama';
-- EXPLAIN SELECT ... FROM t_production_op_dl WHERE id_production = 'X' GROUP BY nama;
-- (should show key: idx_idprod_nama, or a composite that uses it)
