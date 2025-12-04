# Project Context

## Purpose
DPR (Daily Production Report) is a comprehensive PHP-based web application for manufacturing production management. The system tracks daily manufacturing operations including machine performance, material transactions, inventory management, production planning, and generates analytical reports. It's designed for manufacturing environments that need to monitor production efficiency, track material usage, and manage operational data by shift, date, and production lines.

## Tech Stack
- **Backend Framework**: CodeIgniter 3.x (PHP MVC framework)
- **Language**: PHP 5.3.7+ (runs on PHP 3.11.0)
- **Database**: MySQL/MySQLi with CodeIgniter Query Builder
- **Frontend**: Bootstrap 3.x, jQuery 1.x, DataTables for data tables
- **Reporting**: PHPExcel library for Excel export functionality
- **Web Server**: Apache/Nginx (configured for Laravel/PHP applications)
- **Environment**: Windows development environment with Laragon stack

### Key Libraries
- **PHPExcel**: Excel file generation and export
- **DataTables**: Interactive data tables with filtering and pagination
- **Bootstrap**: Responsive UI framework
- **jQuery**: JavaScript DOM manipulation and AJAX

## Project Conventions

### Code Style
- **PHP Standards**: Follow PSR-1 and PSR-2 for PHP coding standards
- **Naming Conventions**:
  - Controllers: `c_[feature].php` (e.g., `c_dpr.php`, `c_inventory.php`)
  - Models: `m_[feature].php` (e.g., `m_dpr.php`, `m_inventory.php`)
  - Views: Organized by feature in subdirectories (e.g., `views/dpr/`, `views/inventory/`)
  - Database tables: Lowercase with underscores
  - Variables: snake_case for PHP variables
- **File Organization**: CodeIgniter MVC pattern with clear separation of concerns
- **Security**: All controllers require login verification via `$this->mm->cek_login()`
- **Database Access**: Use CodeIgniter Query Builder or prepared statements only

### Architecture Patterns
- **MVC Architecture**: Strict separation of Models (data), Views (presentation), and Controllers (logic)
- **Session Management**: CodeIgniter session library with user authentication storing `user_name`, `divisi`, and `posisi`
- **Role-based Access**: User positions (Operator, Kasi, Kanit) determine access levels
- **Modular Design**: Feature-based organization (DPR, Inventory, Machine, Reports, etc.)
- **Data Validation**: CodeIgniter form validation library for input sanitization

### Testing Strategy
- **Current State**: Limited automated testing infrastructure
- **Manual Testing**: Test files exist but have been cleaned up for production
- **Recommended**: Implement PHPUnit integration (already available as dev dependency)
- **Testing Areas**: Focus on critical business logic, data calculations, and form validation

### Git Workflow
- **Main Branch**: `master` (production)
- **Commit Style**: Descriptive commit messages focusing on "why" rather than "what"
- **Feature Development**: Work in feature branches when implementing significant changes
- **Clean Development**: Regular cleanup of test files and temporary documentation

## Domain Context

### Manufacturing Domain Knowledge
- **Production Shifts**: Manufacturing operates in shifts (typically 8-hour periods)
- **Machine Performance**: Track productivity, downtime, and efficiency metrics
- **Material Transactions**: Track raw material usage, inventory levels, and waste
- **Production Planning**: Schedule and track planned vs actual production
- **Quality Metrics**: Monitor defect rates, production quality, and compliance
- **User Roles**: 
  - **Operator**: Machine operators who input daily production data
  - **Kasi**: Supervisors who review and approve production data
  - **Kanit**: Department heads with oversight responsibilities
  - **Admin**: System administrators for user and configuration management

### Key Business Concepts
- **DPR (Daily Production Report)**: Central data entity capturing shift-level production data
- **PPM (Parts Per Million)**: Quality metrics for defect measurement
- **Production Time**: Machine uptime vs downtime tracking
- **Material Usage**: Raw material consumption and inventory management
- **Planning vs Actual**: Comparison of planned production targets with actual output

## Important Constraints

### Technical Constraints
- **PHP Version**: Must support PHP 5.3.7+ (currently running on PHP 3.11.0)
- **Database**: MySQL/MySQLi specific (no PDO compatibility required)
- **Legacy Code**: CodeIgniter 3.x framework (not upgraded to newer versions)
- **File Upload Limits**: PHPExcel library constraints for Excel file handling
- **Memory Management**: Large data exports need memory optimization

### Business Constraints
- **Data Integrity**: Production data must be accurate and auditable
- **User Access**: Strict role-based access control for sensitive production data
- **Reporting Requirements**: Daily, weekly, and monthly production reports mandatory
- **Data Retention**: Historical production data must be preserved for compliance
- **Performance**: System must handle concurrent multiple shift data entry

### Regulatory Constraints
- **Manufacturing Compliance**: Must support quality control documentation
- **Audit Trail**: All production data changes must be traceable
- **Data Privacy**: Employee information protection requirements

## External Dependencies

### Core Dependencies
- **CodeIgniter 3.x**: MVC framework (managed via Composer)
- **PHPExcel**: Excel file generation and processing
- **MySQL Database**: Data persistence layer
- **Web Server**: Apache/Nginx with PHP support

### Development Tools
- **Composer**: PHP dependency management
- **PHPUnit**: Testing framework (dev dependency)
- **Git**: Version control system

### Production Infrastructure
- **Laragon Stack**: Local development environment
- **Database Server**: MySQL/MariaDB instance
- **File Storage**: Local filesystem for uploads and exports
- **Backup System**: Database backup and recovery procedures

### Potential External Integrations
- **ERP Systems**: Future integration possibilities with enterprise resource planning
- **SCADA/Industrial Systems**: Real-time machine data integration
- **Accounting Systems**: Financial data synchronization
- **Quality Management Systems**: Compliance and quality data integration
