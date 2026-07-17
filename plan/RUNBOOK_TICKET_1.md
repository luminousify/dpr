# Ticket 1 Runbook - Production Quick Wins

This is the operations checklist for the parts of Ticket 1 that **cannot be
shipped in code** (they touch the server, not the repo). The code parts
(insert_batch refactor, `save_queries` conditional, `Dpr_batch_builder` library)
are already committed; this file covers the deploy-time tasks.

## 1. ENVIRONMENT = production on the server

`index.php` reads the environment from the `CI_ENV` server variable, defaulting
to `development`:

```php
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
```

On the production server (Apache, IIS, or whichever webserver fronts the app),
set `CI_ENV=production`. The repo's `.htaccess` already exists - add this line
to it (Apache mod_setenvif / mod_env):

```apache
SetEnv CI_ENV production
```

For IIS, use `<environmentVariables>` in `web.config`:

```xml
<environmentVariables>
  <add name="CI_ENV" value="production" />
</environmentVariables>
```

**Verify after deploy** (temporarily, then remove):
- `var_dump(ENVIRONMENT);` should print `string(10) "production"`.
- Error pages should not show a stack trace.

## 2. save_queries = FALSE in production

`application/config/database.php` (line may shift) was changed from:
```php
'save_queries' => TRUE
```
to:
```php
'save_queries' => (ENVIRONMENT !== 'production')
```

This file is `.gitignore`d (it holds live DB credentials). On each environment
(dev, staging, prod), apply the same change to that environment's local copy.
With task 1 above setting `ENVIRONMENT = production` on the server, the
conditional will resolve to `FALSE` automatically in production and stay `TRUE`
in dev.

**Verify after deploy**: in dev, `$this->db->last_query()` still works; in
production, `save_queries` resolves to `false` (no high-memory query log).

## 3. Composite index on t_production_op_dl

Apply `plan/migrations/001_composite_index_t_production_op_dl.sql` against the
production `dpr_cku` database. The SQL is idempotent (guarded by an
INFORMATION_SCHEMA check) and safe to re-run.

```bash
mysql -h <prod_host> -P <prod_port> -u <user> -p dpr_cku < plan/migrations/001_composite_index_t_production_op_dl.sql
```

**Verify after deploy**:
```sql
SHOW INDEX FROM t_production_op_dl WHERE Key_name = 'idx_idprod_nama';
-- Should return one row.

-- Run EXPLAIN on a representative pivot query and confirm the optimizer
-- picks idx_idprod_nama (or a composite that covers it):
EXPLAIN SELECT nama, COUNT(*)
FROM t_production_op_dl
WHERE id_production = '<a real id>'
GROUP BY nama;
```

**Rollback** (if a regression appears):
```sql
DROP INDEX idx_idprod_nama ON t_production_op_dl;
```

## 4. Code changes (already committed, just deploy)

- `application/libraries/dpr_batch_builder.php` - new pure batch-row builder.
- `application/models/m_dpr.php` - `add()` and `sync_cutting_tools_by_production()` now use `insert_batch()`.
- `application/models/m_operator.php` - `add()`, `add_cutting_tools_usage()`, `insert_cutting_tool_usages()` now use `insert_batch()`.
- `composer.json`, `phpunit.xml`, `tests/` - new test harness for the batch builder (dev-only; `/vendor/` is gitignored so PHPUnit must be installed on the server only if you want tests there).

Note: `application/config/database.php` is **not** tracked by git (it holds live DB credentials and is in `.gitignore`). The `save_queries` conditional from task 2 must be applied to each environment's local copy of that file by hand; see section 2 above.

## Acceptance for Ticket 1

- [ ] Production ENVIRONMENT is `production` (no stack traces on error pages).
- [ ] Production `save_queries` resolves to `FALSE` (verified in dev that the conditional flips correctly).
- [ ] `SHOW INDEX FROM t_production_op_dl` includes `idx_idprod_nama`.
- [ ] `EXPLAIN` on the pivot query uses the new index.
- [ ] Operator form (`login_op/input_dpr` -> `c_operator/add`) still inserts rows correctly.
- [ ] Admin form (`c_dpr/add_dpr` -> `c_dpr/add`) still inserts rows correctly.
- [ ] Query log shows `INSERT INTO ... VALUES (...), (...), (...)` batch syntax instead of one INSERT per row.
