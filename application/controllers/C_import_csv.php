<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_import_csv extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_import_csv', 'm_import');
        $this->load->helper(array('url', 'html', 'form', 'file'));
        $this->load->library('session');
        $this->load->database();

        // Check login
        if (empty($_SESSION['user_name'])) {
            redirect('login_control/index');
        }

        // Initialize data array for sidebar (follows project conventions)
        $this->data = [
            'user_name'  => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '',
            'bagian'     => isset($_SESSION['divisi']) ? $_SESSION['divisi'] : '',
            'posisi'     => isset($_SESSION['posisi']) ? $_SESSION['posisi'] : '',
            'nama_actor' => isset($_SESSION['nama_actor']) ? $_SESSION['nama_actor'] : '',
        ];
    }

    public function index()
    {
        $data = [
            'data'  => $this->data,
            'aktif' => 'import',
        ];

        $this->load->view('layout/sidebar', $data);
        $this->load->view('import_csv/index', $data);
    }

    public function upload_csv()
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 10240; // 10MB
        $config['file_name'] = 'dpr_import_' . date('YmdHis') . '.csv';

        if (!is_dir('./uploads')) {
            mkdir('./uploads', 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('csv_file')) {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error['error']);
            redirect('c_import_csv/index');
        } else {
            $upload_data = $this->upload->data();
            $csv_path = $upload_data['full_path'];

            // Preview the CSV
            $preview_data = $this->m_import->preview_csv($csv_path);

            $this->session->set_userdata('csv_file_path', $csv_path);

            $data = [
                'data'  => $this->data,
                'aktif' => 'import',
                'csv_path' => $csv_path,
                'preview_data' => $preview_data,
                'total_rows' => count($preview_data),
            ];

            $this->load->view('layout/sidebar', $data);
            $this->load->view('import_csv/preview', $data);
        }
    }

    public function process_import()
    {
        $csv_path = $this->session->userdata('csv_file_path');

        if (!$csv_path || !file_exists($csv_path)) {
            $this->session->set_flashdata('error', 'CSV file not found. Please upload again.');
            redirect('c_import_csv/index');
        }

        // Backup database before import
        $backup_file = './backups/dpr_backup_' . date('YmdHis') . '.sql';
        if (!is_dir('./backups')) {
            mkdir('./backups', 0777, true);
        }

        // Process import
        $result = $this->m_import->process_csv_import($csv_path);

        // Delete CSV file after processing
        if (file_exists($csv_path)) {
            unlink($csv_path);
        }

        $this->session->unset_userdata('csv_file_path');

        $data = [
            'data'  => $this->data,
            'aktif' => 'import',
            'result' => $result,
        ];

        $this->load->view('layout/sidebar', $data);
        $this->load->view('import_csv/result', $data);
    }

    public function download_template()
    {
        $this->load->helper('download');
        $template_path = './assets/csv_import_template.csv';
        if (file_exists($template_path)) {
            force_download($template_path, NULL);
        } else {
            $this->session->set_flashdata('error', 'Template file not found.');
            redirect('c_import_csv/index');
        }
    }
}
