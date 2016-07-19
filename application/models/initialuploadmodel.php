<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/datatable.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of initialuploadmodel
 *
 * @author mozar
 */


class InitialUploadModel extends datatable {

    function insertFromExcel($data, $fileName) {
        for ($i = 0, $n = count($data); $i < $n; $i++) {
            $data[$i] = mysql_real_escape_string($data[$i]);
        }
        $fileName = mysql_real_escape_string($fileName);
        $sqlTanggalLahir = "',NULL,'";
        if (strlen($data[10]) > 0) {
            $sqlTanggalLahir = "',STR_TO_DATE('" . $data[10] . "','%d/%m/%Y'),'";
        }
        $sql = "insert into initial_upload (PROVINSI_NAMA,KABUPATEN_NAMA,KECAMATAN_NAMA,DESA_NAMA,
                ALAMAT,KK,NIK,NAMA,TEMPAT_LAHIR,TANGGAL_LAHIR,USIA,JENIS_KELAMIN,HUBUNGAN_KELUARGA,
                SEKOLAH,CACAT,HAMIL,PENGHASILAN_PERBULAN,STATUS_KAWIN,NAMA_FILE_UPLOAD) VALUES ('"
                . $data[1] . "','" . $data[2] . "','" . $data[3] . "','" . $data[4] . "','"
                . $data[5] . "','" . $data[6] . "','" . $data[7] . "','" . $data[8] . "','" . $data[9] . $sqlTanggalLahir . $data[11] . "','" . $data[12] . "','" . $data[13] . "','"
                . $data[14] . "','" . $data[15] . "','" . $data[16] . "','" . $data[17] . "','" . $data[18] . "','" . $fileName . "')";
        
        $this->db->query($sql);
        //$this->db->insert('initial_upload', $data);
        return $this->db->insert_id();
    }

    public function get_list_hasil_upload_datatable($request) {
        $sql = "select * from initial_upload";
        $columns = array(
            0=>array('name' => 'NAMA_FILE_UPLOAD'),
            1=>array('name' => 'ID'),
            2=>array('name' => 'NAMA'),
            3=>array('name' => 'PROVINSI_NAMA'),
            4=>array('name' => 'KABUPATEN_NAMA'),
            5=>array('name' => 'KECAMATAN_NAMA'),
            6=>array('name' => 'DESA_NAMA'),
            7=>array('name' => 'ALAMAT'),
            8=>array('name' => 'NIK'),
            9=>array('name' => 'KK'),
            10=>array('name' => 'TEMPAT_LAHIR'),
            11=>array('name' => 'TANGGAL_LAHIR'),
            12=>array('name' => 'PROVINSI_ID'),
            13=>array('name' => 'KABUPATEN_ID'),
            14=>array('name' => 'KECAMATAN_ID'),
            15=>array('name' => 'DESA_ID'),
        );
        return $this->get_datatable($sql, $columns, $request);
    }

}
