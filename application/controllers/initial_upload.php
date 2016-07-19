<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/admin_controller.php';

class Initial_Upload extends admin_controller {

    public $data = array('title' => 'initial upload');

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('initial_upload/view_index', $this->data);
    }

    public function hasil_upload_page() {
        $this->data['title'] = "hasil upload";
        $this->load->view('initial_upload/view_hasil_upload', $this->data);
    }

    function simpan_perubahan() {
        $id_provinsi = intval($this->input->post('provinsi'));
        $id_kabupaten = intval($this->input->post('kabupaten'));
        $id_kecamatan = intval($this->input->post('kecamatan'));
        $id_desa = intval($this->input->post('desa'));
        $res = array('res' => 'fail', 'reason' => 'unknown');
        $update = array();
        $q = $this->db->query("select * from master_provinsi where PROVINSI_ID=?", array($id_provinsi))->result_array();
        if (count($q) <= 0) {
            $res['reason'] = "Provinsi tidak dapat ditemukan";
            echo json_encode($res);
            exit(0);
        }
        $provinsi = $q[0];
        $update['PROVINSI_ID'] = $provinsi['PROVINSI_ID'];
        $level_desa = false;
        if ($id_kabupaten > 0) {
            $q = $this->db->query("select * from master_kabupaten where KABUPATEN_ID=? and PROVINSI_ID=?", array($id_kabupaten, $provinsi['PROVINSI_ID']))->result_array();
            if (count($q) > 0) {
                $kabupaten = $q[0];
//                $update.=" , KABUPATEN_ID='" . $kabupaten['KABUPATEN_ID'] . "' ";
                $update['KABUPATEN_ID'] = $kabupaten['KABUPATEN_ID'];
                if ($id_kecamatan > 0) {
                    $q = $this->db->query("select * from master_kecamatan where KECAMATAN_ID=? and KABUPATEN_ID=?", array($id_kecamatan, $kabupaten['KABUPATEN_ID']))->result_array();
                    if (count($q) > 0) {
                        $kecamatan = $q[0];
//                        $update.=" , KECAMATAN_ID='" . $kecamatan['KECAMATAN_ID'] . "' ";
                        $update['KECAMATAN_ID'] = $kecamatan['KECAMATAN_ID'];
                        if ($id_desa > 0) {
                            $q = $this->db->query("select * from master_desa where DESA_ID=? and KECAMATAN_ID=?", array($id_desa, $kecamatan['KECAMATAN_ID']))->result_array();
                            if (count($q) > 0) {
                                $desa = $q[0];
//                                $update.=" , DESA_ID='" . $desa['DESA_ID'] . "' ";
                                $update['DESA_ID'] = $desa['DESA_ID'];
                                $level_desa = true;
                            }
                        }
                    }
                }
            }
        }
        $update_mode = $this->input->post('mode_simpan');
        $id_upload = $this->input->post('id_upload');
        if ($update_mode == 'terpilih') {
            $update_string = "";
            $sep = '';
            $update_array = array();
            foreach ($update as $k => $u) {
                $update_string.=$sep . $k . '=?';
                $update_array[] = $u;
                $sep = ',';
            }
            foreach ($id_upload as $id) {
                $id = intval($id);
                $this->db->query("update initial_upload set $update_string where ID=?", array_merge($update_array, array($id)));
            }
            if ($level_desa) {
                foreach ($id_upload as $id) {
                    $id = intval($id);
                    $q = $this->db->query("select * from initial_upload where ID=?", array($id))->result_array();
                    if (count($q) > 0) {
                        $u = $q[0];
                        $this->db->query("insert into penduduk (PROVINSI_ID,KABUPATEN_ID,KECAMATAN_ID,DESA_ID,ALAMAT,KK,NIK,NAMA,TEMPAT_LAHIR,TANGGAL_LAHIR,USIA,JENIS_KELAMIN,HUBUNGAN_KELUARGA,SEKOLAH,CACAT,HAMIL,PENGHASILAN_PERBULAN,STATUS_KAWIN) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ", array($u['PROVINSI_ID'], $u['KABUPATEN_ID'], $u['KECAMATAN_ID'], $u['DESA_ID'], $u['ALAMAT'], $u['KK'], $u['NIK'], $u['NAMA'], $u['TEMPAT_LAHIR'], $u['TANGGAL_LAHIR'], $u['USIA'], $u['JENIS_KELAMIN'], $u['HUBUNGAN_KELUARGA'], $u['SEKOLAH'], $u['CACAT'], $u['HAMIL'], $u['PENGHASILAN_PERBULAN'], $u['STATUS_KAWIN']));
                    }
                    $this->db->query("delete from initial_upload where ID=?", array($id));
                }
            }
        } else if ($update_mode == 'kesamaan') {
            $update_string = "";
            $sep = '';
            $sep2 = '';
            $update_array = array();
            $filter_string = '';
            foreach ($update as $k => $u) {
                $update_string.=$sep . $k . '=? ';
                $update_array[] = $u;
                $sep = ',';
                $sep2 = ' and ';
            }
            $upload_item = null;



            foreach ($id_upload as $id) {
                $id = intval($id);
                $q = $this->db->query("select * from initial_upload where ID=?", array($id))->result_array();
                if (count($q) > 0) {
                    $upload_item = $q[0];
                    $filter_array = array($upload_item['PROVINSI_NAMA']);
                    $filter_string.="PROVINSI_NAMA=?";
                    $jumlah_field = count($update_array);
                    if ($jumlah_field >= 2) {
                        $filter_array[] = $upload_item['KABUPATEN_NAMA'];
                        $filter_string.=" AND KABUPATEN_NAMA=?";
                    }
                    if ($jumlah_field >= 3) {
                        $filter_array[] = $upload_item['KECAMATAN_NAMA'];
                        $filter_string.=" AND KECAMATAN_NAMA=?";
                    }
                    if ($jumlah_field == 4) {
                        $filter_array[] = $upload_item['DESA_NAMA'];
                        $filter_string.=" AND DESA_NAMA=?";
                    }
                    break;
                }
            }
            if ($upload_item != null) {
                if ($level_desa) {
                    $q=$this->db->query("select * from initial_upload where $filter_string",$filter_array)->result_array();
                    foreach ($q as $u){
                        $this->db->query("insert into penduduk (PROVINSI_ID,KABUPATEN_ID,KECAMATAN_ID,DESA_ID,ALAMAT,KK,NIK,NAMA,TEMPAT_LAHIR,TANGGAL_LAHIR,USIA,JENIS_KELAMIN,HUBUNGAN_KELUARGA,SEKOLAH,CACAT,HAMIL,PENGHASILAN_PERBULAN,STATUS_KAWIN,FILE_UPLOAD) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ", array($provinsi['PROVINSI_ID'], $kabupaten['KABUPATEN_ID'], $kecamatan['KECAMATAN_ID'], $desa['DESA_ID'], $u['ALAMAT'], $u['KK'], $u['NIK'], $u['NAMA'], $u['TEMPAT_LAHIR'], $u['TANGGAL_LAHIR'], $u['USIA'], $u['JENIS_KELAMIN'], $u['HUBUNGAN_KELUARGA'], $u['SEKOLAH'], $u['CACAT'], $u['HAMIL'], $u['PENGHASILAN_PERBULAN'], $u['STATUS_KAWIN'],$u['NAMA_FILE_UPLOAD']));
                        $this->db->query("delete from initial_upload where ID=?",array($u['ID']));
                    }
                } else {
                    //  $res[] = "update initial_upload set $update_string where $filter_string";
                    //$res[] = array_merge($update_array, $filter_array);
                    $this->db->query("update initial_upload set $update_string where $filter_string", array_merge($update_array, $filter_array));
                }
            }
        }
        $res['res'] = 'ok';
        echo json_encode($res);
    }
    
    function hapus(){
        $id_upload = $this->input->post('id');
        foreach($id_upload as $idu){
            $this->db->query("delete from initial_upload where ID=?",array(intval($idu)));
        }
    }

    public function get_list_hasil_upload_datatable() {
        $this->load->model(array('InitialUploadModel'));
        echo json_encode($this->InitialUploadModel->get_list_hasil_upload_datatable($_POST));
    }

    public function proses_upload() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $this->load->library(array('myuploadlib', 'excel'));
        $this->load->model(array('InitialUploadModel'));
        $myUpload = new MyUploadLib();
        $myUpload->prosesUpload('fileExcel', 'initial');
        $uploadedFile = $myUpload->getUploadedFiles();
        $sheetToProcess = $this->input->post('sheetProcess');
        //echo '$sheetToProcess';
        //print_r($sheetToProcess);
        $nExcel = count($uploadedFile);
        echo 'jumlah file terupload '.$nExcel;
        $processedFiles = array();
        for ($i = 0; $i < $nExcel; $i++) {
            $sheet = array(1);
            if (is_array($sheetToProcess) && count($sheetToProcess) > $i) {
                $sheet = explode(',', $sheetToProcess[$i]);
                $nSheet = count($sheet);
                for ($j = 0; $j < $nSheet; $j++) {
                    if (is_numeric($sheet[$j]) == false) {
                        unset($sheet[$j]);
                    }
                }
            }
            $extensi = strtolower(pathinfo($uploadedFile[$i]['filePath'], PATHINFO_EXTENSION));
            if ($extensi == 'xls' || $extensi == 'xlsx') {
                //echo 'processing file #' . $uploadedFile[$i]['name'] . '<br/>';
                $processedFiles[] = $uploadedFile[$i];
                $this->processExcelDocument($uploadedFile[$i]['filePath'], $sheet);
            }else{
                echo 'extensi fie tidak dapat diproses';
            }
        }
        //$myUpload->deleteUploadedFiles();
        //redirect(site_url() . '/initial_upload');
        $this->data['uploadedFiles'] = $uploadedFile;
        $this->data['errors'] = $myUpload->getErrors();
        $this->data['title'] = "Hasil Upload";
        $this->data['processedFiles'] = $processedFiles;
        $this->load->view('initial_upload/view_upload_response', $this->data);
    }

    private function processExcelDocument($fileExcel, $pSheet) {
        //print_r('prosesDokumenExcel');
        //print_r($pSheet);
        $xls = PHPExcel_IOFactory::load($fileExcel);
        //print_r($xls);
        $nSheet = $xls->getSheetCount();
        echo 'jumlah sheet dalam file = '.$nSheet;
        foreach ($pSheet as $noSheet) {
            if ($noSheet > $nSheet) {
                print_r('sheet #' . $noSheet . ' not processed');
                continue;
            }
            echo 'processing sheet #' . $noSheet . '<br/>';
            $sheet = $xls->getSheet($noSheet-1);
            //print_r($sheet);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $this->processSheet($sheet, $fileExcel);
        }
    }
    private $contoh=true;
    private function processSheet($sheet, $fileName) {
        $fileName = basename($fileName);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 4; $row < $highestRow; $row++) {
            $sebaris = $sheet->rangeToArray('A' . $row . ':U' . $row);
            //print_r($sebaris[0]);
            //break;
            // if($this->contoh){
            //     $this->contoh=false;
            //     print_r($sebaris);
            // }
            if($sebaris[0][20] =='1')
                $this->InitialUploadModel->insertFromExcel($sebaris[0], $fileName);
            
        }
    }

    function hapus_semua(){
        if($this->db->query("delete from initial_upload")=='1'){
            echo 'ok';
        }else{
            echo 'Gagal menghapus data hasil upload';
        }
    }

}
