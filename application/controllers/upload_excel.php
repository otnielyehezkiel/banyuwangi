<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

require_once APPPATH . '/libraries/admin_controller.php';
class Upload_excel extends admin_controller {
    public $data = array('title' => 'initial upload');

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }
    
    public function get($jenis=0){
        if($jenis){
            $this->load->view('initial_upload/view_upload_'.$jenis, $this->data);
        }  
        else $this->load->view('initial_upload/view_upload_produksi', $this->data);
    }

    public function proses()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        
        $this->load->library(array('myuploadlib', 'excel'));
        $this->load->model(array('InitialUploadModel'));
        $myUpload = new MyUploadLib();
        $myUpload->prosesUpload('fileExcel', 'initial');
        $uploadedFile = $myUpload->getUploadedFiles();
        
        $this->load->model('excelmodel');
        $arr_data=$this->excelmodel->getExcelData($uploadedFile[0]['filePath']);
        $this->data["filepath"]=$uploadedFile[0]['filePath'];
        
        return $arr_data;
    }
    
    public function hasilupload()
    {
        $hasil=$this->proses();
        $this->data["title"]="Hasil Upload";
        $this->data["header"]=$hasil["header"];
        $this->data["data_val"]=$hasil["arr_data"];
        $this->data['nama'] = $this->input->post('namajenis');
        $this->data['jenis_data']=$this->input->post('jenisdata');
        $this->data['table']=$this->input->post('table');
        $this->load->view('initial_upload/view_hasil_upload',$this->data);

    }
}