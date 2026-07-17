-- Ticket 2 / Phase 1.1: Create the t_sync_queue table.
--
-- Purpose: async write queue for the PWA sync endpoint. The fast-accept
-- controller (api/Sync::submit) inserts one row per payload here in <50ms;
-- the drain worker (Ticket 3 / c_sync::queue_drain) later moves each row's
-- payload into the real tables (t_production_op, t_production_op_dl,
-- t_production_op_release, t_production_op_cutting_tools_usage).
--
-- Schema is copied verbatim from plan/SPEC_PWA_QUEUE.md section 1.1; only
-- `IF NOT EXISTS` is added for idempotency, mirroring the Ticket 1
-- migration convention. Compatible with MySQL 5.7+ / MariaDB 10.2+ (JSON
-- column type) and MySQL 8.x.
--
-- The UNIQUE KEY uk_id_production gives us two properties for free:
--   1. Submit idempotency - a PWA retry of the same id_production does not
--      create a second queue row (submit() treats the duplicate as success).
--   2. Drain idempotency - the drain worker can re-run a batch safely; the
--      UNIQUE KEY prevents the same production record being inserted twice.

CREATE TABLE IF NOT EXISTS t_sync_queue (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_production VARCHAR(50) NOT NULL,
  payload JSON NOT NULL,
  status ENUM('pending', 'processing', 'done', 'error', 'duplicate_review') DEFAULT 'pending',
  error_message TEXT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  processed_at DATETIME NULL,
  UNIQUE KEY uk_id_production (id_production)
);

-- Verify:
-- DESCRIBE t_sync_queue;
-- SHOW INDEX FROM t_sync_queue WHERE Key_name = 'uk_id_production';
