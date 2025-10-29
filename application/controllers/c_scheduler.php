<?php 
require 'vendor/autoload.php';
use WebSocket\Client;
class c_scheduler extends CI_Controller {
    public function __construct()
    {
    parent::__construct();
    $this->load->model('m_scheduler' , 'm');
    }

    function ping() {
        set_time_limit(500);
        $query = $this->db->query("SELECT q.`id_machine_use`,q.`no_mesin`, r.`ip` FROM machine_use q
            LEFT JOIN t_no_mesin_copy r ON q.`no_mesin` = r.`no_mesin`
            WHERE q.`send` = 0");
            function convertIntegerToString($number) {
                // Format the integer with leading zeros
                $formattedNumber = sprintf("%02d", $number);
                
                // Concatenate 'MC' with the formatted number
                $result = 'MC' . $formattedNumber;
                
                return $result;
            }
    
        if ($query->num_rows() > 0) {
            // Reuse the cURL handle
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
    
            foreach ($query->result() as $data_user) {
                $result = convertIntegerToString($data_user->no_mesin);
                $serverUrl = 'ws://' . $data_user->ip;
                $url = 'http://192.168.1.57/tms/Machine/get/' . $result;
                curl_setopt($curl, CURLOPT_URL, $url);
                $response = curl_exec($curl);
                $message = $response;
    
                // Initialize the WebSocket client with the server URL
                $client = new Client($serverUrl,['timeout' => 500,]);
                $client->send($message);
                $receivedMessage = $client->receive();
                echo 'Received message: ' . $receivedMessage . PHP_EOL;
                $client->close();
                $this->db->set('send', TRUE);
                $this->db->where('id_machine_use', $data_user->id_machine_use);
                $this->db->update('machine_use');
            }
    
            // Close the cURL handle
            curl_close($curl);
        } else {
            echo "Unsend Signal not found";
            return TRUE;
        }
    }

    function send_notif_controller_edit() {
        $id_machine = $this->uri->segment(3, 0);

        set_time_limit(500);
        $query = $this->db->query("SELECT q.`id_machine_use`,q.`no_mesin`, r.`ip` FROM machine_use q
        LEFT JOIN t_no_mesin_copy r ON q.`no_mesin` = r.`no_mesin`
        WHERE q.`send` = 0 AND q.`id_machine_use` = '$id_machine'");
            function convertIntegerToString($number) {
                // Format the integer with leading zeros
                $formattedNumber = sprintf("%02d", $number);
                
                // Concatenate 'MC' with the formatted number
                $result = 'MC' . $formattedNumber;
                
                return $result;
            }
    
        if ($query->num_rows() > 0) {
            // Reuse the cURL handle
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
    
            foreach ($query->result() as $data_user) {
                $result = convertIntegerToString($data_user->no_mesin);
                $serverUrl = 'ws://' . $data_user->ip;
                $url = 'http://192.168.1.57/tms/Machine/get/' . $result;
                curl_setopt($curl, CURLOPT_URL, $url);
                $response = curl_exec($curl);
                $message = $response;
    
                // Initialize the WebSocket client with the server URL
                $client = new Client($serverUrl,['timeout' => 500,]);
                $client->send($message);
                $receivedMessage = $client->receive();
                echo 'Received message: ' . $receivedMessage . PHP_EOL;
                $client->close();
                $this->db->set('send', TRUE);
                $this->db->where('id_machine_use', $data_user->id_machine_use);
                $this->db->update('machine_use');
            }
    
            // Close the cURL handle
            curl_close($curl);
        } else {
            echo "Unsend Signal not found";
            return TRUE;
        }
    }
    
    


    function ping_ori(){
        $query = $this->db->query("SELECT q.`no_mesin`,r.`ip` FROM machine_use_with_controller q
        LEFT JOIN t_no_mesin_copy r
        ON q.`no_mesin` = r.`no_mesin`
        WHERE q.`send` = 0");
        function convertIntegerToString($number) {
            // Format the integer with leading zeros
            $formattedNumber = sprintf("%02d", $number);
            
            // Concatenate 'MC' with the formatted number
            $result = 'MC' . $formattedNumber;
            
            return $result;
        }

        if($query->num_rows() > 0) {
            foreach ($query->result() as $data_user)
        {
            $result = convertIntegerToString($data_user->no_mesin);
            //start
            $serverUrl = 'ws://'.$data_user->ip;
            $client = new Client($serverUrl);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                        CURLOPT_URL => 'http://192.168.1.57/tms/Machine/get/'.$result,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $message = $response;
                        $client->send($message);
                        $receivedMessage = $client->receive();
                        echo 'Received message: ' . $receivedMessage . PHP_EOL;
                        $client->close();

        }
            

            

            //end
        }
        else
        {  
            echo "False if 1";
            return FALSE;
        }
}


}