<?php
/**
 * Test script to verify mp_standar column compatibility
 * This script tests the updated methods to ensure they work with the new mp_standar column
 */

// Include the CodeIgniter framework
require_once 'index.php';

class TestMpStandar {
    private $ci;
    private $m_new;
    private $m_inventory;
    
    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->model('m_new');
        $this->ci->load->model('m_inventory');
        $this->m_new = $this->ci->m_new;
        $this->m_inventory = $this->ci->m_inventory;
    }
    
    public function runTests() {
        echo "=== Testing mp_standar Column Compatibility ===\n\n";
        
        // Test 1: Check if mp_standar column exists in t_product table
        $this->testColumnExists();
        
        // Test 2: Test getDataProduct method
        $this->testGetDataProduct();
        
        // Test 3: Test get_kodeProductRelease method
        $this->testGetKodeProductRelease();
        
        // Test 4: Test tampil_product_bySearch method
        $this->testTampilProductBySearch();
        
        // Test 5: Test tampil_product_default method
        $this->testTampilProductDefault();
        
        // Test 6: Test inventory methods
        $this->testInventoryMethods();
        
        echo "\n=== All Tests Completed ===\n";
    }
    
    private function testColumnExists() {
        echo "Test 1: Checking if mp_standar column exists in t_product table...\n";
        
        $query = $this->ci->db->query("SHOW COLUMNS FROM t_product LIKE 'mp_standar'");
        $result = $query->result();
        
        if (count($result) > 0) {
            echo "✓ mp_standar column exists\n";
            echo "  - Type: " . $result[0]->Type . "\n";
            echo "  - Null: " . $result[0]->Null . "\n";
            echo "  - Default: " . $result[0]->Default . "\n";
        } else {
            echo "✗ mp_standar column does not exist\n";
            echo "  Please run the migration script first\n";
        }
        echo "\n";
    }
    
    private function testGetDataProduct() {
        echo "Test 2: Testing getDataProduct method...\n";
        
        try {
            // Get a sample product ID
            $sample_query = $this->ci->db->query("SELECT id_product FROM t_product LIMIT 1");
            $sample_result = $sample_query->result();
            
            if (count($sample_result) > 0) {
                $id_product = $sample_result[0]->id_product;
                $result = $this->m_new->getDataProduct($id_product);
                
                if ($result && $result->num_rows() > 0) {
                    $product_data = $result->row();
                    echo "✓ getDataProduct method works\n";
                    echo "  - Product ID: " . $product_data->id_product . "\n";
                    echo "  - Product Code: " . $product_data->kode_product . "\n";
                    echo "  - MP Standar: " . (isset($product_data->mp_standar) ? $product_data->mp_standar : 'NULL') . "\n";
                } else {
                    echo "✗ getDataProduct method returned no results\n";
                }
            } else {
                echo "✗ No products found in database\n";
            }
        } catch (Exception $e) {
            echo "✗ getDataProduct method failed: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    private function testGetKodeProductRelease() {
        echo "Test 3: Testing get_kodeProductRelease method...\n";
        
        try {
            $result = $this->m_new->get_kodeProductRelease("");
            
            if (is_array($result) && count($result) > 0) {
                echo "✓ get_kodeProductRelease method works\n";
                $first_item = $result[0];
                echo "  - Sample Product ID: " . $first_item['id'] . "\n";
                echo "  - Sample Product Name: " . $first_item['nama'] . "\n";
                echo "  - Sample MP Standar: " . (isset($first_item['mp_standar']) ? $first_item['mp_standar'] : 'NULL') . "\n";
            } else {
                echo "✗ get_kodeProductRelease method returned no results\n";
            }
        } catch (Exception $e) {
            echo "✗ get_kodeProductRelease method failed: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    private function testTampilProductBySearch() {
        echo "Test 4: Testing tampil_product_bySearch method...\n";
        
        try {
            $result = $this->m_new->tampil_product_bySearch("INJ", "");
            
            if ($result && $result->num_rows() > 0) {
                echo "✓ tampil_product_bySearch method works\n";
                $first_item = $result->row();
                echo "  - Sample Product ID: " . $first_item->id_product . "\n";
                echo "  - Sample Product Code: " . $first_item->kode_product . "\n";
                echo "  - Sample MP Standar: " . (isset($first_item->mp_standar) ? $first_item->mp_standar : 'NULL') . "\n";
            } else {
                echo "✗ tampil_product_bySearch method returned no results\n";
            }
        } catch (Exception $e) {
            echo "✗ tampil_product_bySearch method failed: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    private function testTampilProductDefault() {
        echo "Test 5: Testing tampil_product_default method...\n";
        
        try {
            $result = $this->m_new->tampil_product_default();
            
            if ($result && $result->num_rows() > 0) {
                echo "✓ tampil_product_default method works\n";
                $first_item = $result->row();
                echo "  - Sample Product ID: " . $first_item->id_product . "\n";
                echo "  - Sample Product Code: " . $first_item->kode_product . "\n";
                echo "  - Sample MP Standar: " . (isset($first_item->mp_standar) ? $first_item->mp_standar : 'NULL') . "\n";
            } else {
                echo "✗ tampil_product_default method returned no results\n";
            }
        } catch (Exception $e) {
            echo "✗ tampil_product_default method failed: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
    
    private function testInventoryMethods() {
        echo "Test 6: Testing inventory methods...\n";
        
        try {
            // Test tampil_product
            $result = $this->m_inventory->tampil_product();
            if ($result && $result->num_rows() > 0) {
                echo "✓ tampil_product method works\n";
                $first_item = $result->row();
                echo "  - Sample Product ID: " . $first_item->id_product . "\n";
                echo "  - Sample MP Standar: " . (isset($first_item->mp_standar) ? $first_item->mp_standar : 'NULL') . "\n";
            } else {
                echo "✗ tampil_product method returned no results\n";
            }
            
            // Test getProduk
            $sample_query = $this->ci->db->query("SELECT id_product FROM t_product LIMIT 1");
            $sample_result = $sample_query->result();
            
            if (count($sample_result) > 0) {
                $id_product = $sample_result[0]->id_product;
                $result = $this->m_inventory->getProduk($id_product);
                
                if ($result && $result->num_rows() > 0) {
                    echo "✓ getProduk method works\n";
                    $product_data = $result->row();
                    echo "  - Product ID: " . $product_data->id_product . "\n";
                    echo "  - MP Standar: " . (isset($product_data->mp_standar) ? $product_data->mp_standar : 'NULL') . "\n";
                } else {
                    echo "✗ getProduk method returned no results\n";
                }
            }
            
            // Test get_products_with_mp_standar
            $result = $this->m_inventory->get_products_with_mp_standar();
            if ($result && $result->num_rows() > 0) {
                echo "✓ get_products_with_mp_standar method works\n";
                $first_item = $result->row();
                echo "  - Sample Product ID: " . $first_item->id_product . "\n";
                echo "  - Sample MP Standar: " . $first_item->mp_standar . "\n";
            } else {
                echo "! get_products_with_mp_standar method returned no results (may be normal if no mp_standar values set)\n";
            }
            
        } catch (Exception $e) {
            echo "✗ Inventory methods failed: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }
}

// Run the tests
$test = new TestMpStandar();
$test->runTests();
?>