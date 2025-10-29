# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP-based production reporting system (DPR - Daily Production Report) built on the CodeIgniter 3 framework. The application appears to manage manufacturing/production data, including machine operations, material transactions, inventory tracking, and production planning.

## Technology Stack

- **Framework**: CodeIgniter 3.x
- **Language**: PHP (>= 5.3.7)
- **Database**: MySQL/MySQLi (configured in `application/config/database.php`)
- **Front-end**: Bootstrap, jQuery, DataTables
- **Libraries**: PHPExcel for Excel file handling

## Application Architecture

The application follows CodeIgniter's MVC pattern:

- **Controllers** (`application/controllers/`): Handle HTTP requests and business logic
  - `c_dpr.php` - Daily Production Report management
  - `c_inventory.php` - Inventory management
  - `c_machine.php` - Machine operations
  - `c_material_transaction.php` - Material transactions
  - `c_production_plan.php` - Production planning
  - `c_report.php` - Reporting functionality
  - `login_control.php` - User authentication

- **Models** (`application/models/`): Database interaction layer
  - `m_dpr.php` - DPR data operations
  - `m_inventory.php` - Inventory data operations
  - `m_machine.php` - Machine data operations
  - Other models follow similar naming convention (`m_*.php`)

- **Views** (`application/views/`): UI templates organized by feature
  - `dpr/` - DPR-related views
  - `machine/` - Machine management views
  - `inventory/` - Inventory views
  - `report/` - Reporting views
  - `master/` - Master data management views

## Key Features

1. **Daily Production Reporting** - Track production output by shift, machine, and date
2. **Machine Management** - Monitor machine performance and productivity
3. **Inventory Tracking** - Manage material inventory and transactions
4. **Production Planning** - Plan and schedule production runs
5. **Reporting & Analytics** - Generate various production and performance reports
6. **User Authentication** - Role-based access control (Operator, Kasi, Kanit positions)

## Development Commands

### Running the Application
```bash
# This is a PHP application, typically served via Apache/Nginx
# Ensure web server points to the project root
# Access via browser: http://your-server/dpr/
```

### Database Setup
- Configure database connection in `application/config/database.php`
- Database migrations are managed via `application/config/migration.php`

### Dependencies
```bash
# Install PHP dependencies via Composer
composer install
```

## Important Configuration Files

- `application/config/config.php` - Main application configuration
- `application/config/database.php` - Database connection settings
- `application/config/routes.php` - URL routing configuration
- `index.php` - Application entry point and environment settings

## Session Management

The application uses CodeIgniter's session library with user authentication storing:
- `user_name` - Username
- `divisi` - Department/Division
- `posisi` - Position (Operator, Kasi, Kanit, etc.)

## File Upload/Export

- Excel file handling via PHPExcel library
- Production plan template: `assets/Format Excel Production Plan.xls`

## Security Notes

- All controllers check login status via `$this->mm->cek_login()`
- CSRF protection should be enabled in `config.php`
- Input validation required for all user inputs
- Database queries should use Query Builder or prepared statements