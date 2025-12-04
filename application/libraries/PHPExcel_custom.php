<?php
/**
 * PhpExcel - Alternative implementation for older PHP versions
 * Provides basic Excel-like functionality without Composer dependencies
 */
if (!class_exists('PhpExcel')) {
    // Define the class if it doesn't exist
    class PhpExcel {
        protected $objPHPExcel;
        protected $sheet;
        
        public function __construct() {
            // Create new spreadsheet object
            $this->objPHPExcel = new stdClass();
        }
        
        public function createExcel($data = null, $filename = 'export.xlsx')
        {
            // Set headers
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            
            // Open output stream
            $output = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fwrite($output, "\xEF\xBB\xBF");
            
            // Write CSV data as Excel XML-like content
            $this->writeAsExcelXML($output, $data);
            
            fclose($output);
            exit;
        }
        
        protected function writeAsExcel($output, $data)
        {
            // Create a minimal Excel XML structure
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE spreadsheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
            $xml .= '<office:document xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
            
            // Create worksheet
            $xml .= '<office:document><office:worksheet>';
            
            // Add headers
            $headers = [
                'YY', 'Product ID', 'Product Name', 'Max SPM-Std', 'Max SPM Std2', 
                'Tool', 'Min SPM-Set', 'Max SPM-Set', 
                '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'
            ];
            
            // Add header row
            $xml .= '<row>';
            foreach ($headers as $header) {
                $xml .= '<c t="s">' . htmlspecialchars($header) . '</c>';
            }
            $xml .= '</row>';
            
            // Add data rows
            foreach ($data as $row) {
                $xml .= '<row>';
                foreach ($row as $key => $value) {
                    if ($value === null) {
                        $value = '';
                    } elseif (is_numeric($value)) {
                        $value = number_format($value, 2);
                    }
                    $xml .= '<c t="s">' . htmlspecialchars($value) . '</c>';
                }
                $xml .= '</row>';
            }
            
            $xml .= '</office:worksheet></office:document>';
            
            $xml .= '</office:document>';
            
            // Close XML
            fwrite($output, $xml);
        }
        
        public function __get($name)
        {
            return get_class($this);
        }
    }
}
?>
