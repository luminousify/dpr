<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PHPExcel Loader
 * 
 * Properly loads the PHPExcel library with all required dependencies
 * to prevent conflicts with custom stub implementations
 */
class PHPExcel_loader {
    
    private static $loaded = false;
    
    /**
     * Load the PHPExcel library
     * 
     * @return bool True if successful, false otherwise
     */
    public static function load() {
        if (self::$loaded) {
            return true;
        }
        
        try {
            // Include the main PHPExcel file from PHPExcelOld directory
            if (file_exists(APPPATH . 'libraries/PHPExcelOld/PHPExcel.php')) {
                require_once(APPPATH . 'libraries/PHPExcelOld/PHPExcel.php');
                self::$loaded = true;
                return true;
            } elseif (file_exists(APPPATH . 'libraries/PHPExcel/Autoloader.php')) {
                // Alternative: Use the PHPExcel directory
                require_once(APPPATH . 'libraries/PHPExcel/Autoloader.php');
                self::$loaded = true;
                return true;
            } else {
                log_message('error', 'PHPExcel library not found in either PHPExcelOld or PHPExcel directories');
                return false;
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load PHPExcel library: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if PHPExcel is properly loaded
     * 
     * @return bool True if loaded and usable
     */
    public static function is_loaded() {
        return self::$loaded && class_exists('PHPExcel');
    }
    
    /**
     * Create a new PHPExcel instance
     * 
     * @return PHPExcel|null PHPExcel instance or null if failed
     */
    public static function create() {
        if (!self::load()) {
            return null;
        }
        
        try {
            return new PHPExcel();
        } catch (Exception $e) {
            log_message('error', 'Failed to create PHPExcel instance: ' . $e->getMessage());
            return null;
        }
    }
}

// Autoload the PHPExcel when this file is included
PHPExcel_loader::load();
