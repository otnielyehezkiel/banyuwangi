<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of desa_model
 *
 * @author mozar
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . '/libraries/datatable.php';

class master_model extends datatable {
	function get_list_cacat_datatable($request){
        $sql="select * from master_cacat";
        $columns = array(
            0=>array('name' => 'CACAT_ID'),
            1=>array('name' => 'CACAT_KODE'),
            2=>array('name' => 'CACAT_NAMA'),
            3=>array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
    }
    function get_list_hamil_datatable($request){
        $sql="select * from master_hamil";
        $columns = array(
            0=>array('name' => 'HAMIL_ID'),
            1=>array('name' => 'HAMIL_KODE'),
            2=>array('name' => 'HAMIL_NAMA'),
            3=>array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
    }
    function get_list_hubungan_keluarga_datatable($request){
        $sql="select * from master_hubungan_keluarga";
        $columns = array(
            0=>array('name' => 'HUBUNGAN_KELUARGA_ID'),
            1=>array('name' => 'HUBUNGAN_KELUARGA_KODE'),
            2=>array('name' => 'HUBUNGAN_KELUARGA_NAMA'),
            3=>array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
    }
    function get_list_jenis_kelamin_datatable($request){
        $sql="select * from master_jenis_kelamin";
        $columns = array(
            0=>array('name' => 'JENIS_KELAMIN_ID'),
            1=>array('name' => 'JENIS_KELAMIN_KODE'),
            2=>array('name' => 'JENIS_KELAMIN_NAMA'),
            3=>array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
    }
    function get_list_kawin_datatable($request){
		$sql="select * from master_kawin";
		$columns = array(
            0=>array('name' => 'KAWIN_ID'),
            1=>array('name' => 'KAWIN_KODE'),
            2=>array('name' => 'KAWIN_NAMA'),
            3=>array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
	}
	function get_list_penghasilan_datatable($request){
		$sql="select * from master_penghasilan";
		$columns = array(
            array('name' => 'PENGHASILAN_ID'),
            array('name' => 'PENGHASILAN_KODE'),
            array('name' => 'PENGHASILAN_NAMA'),
            array('name' => 'PENGHASILAN_BAWAH'),
            array('name' => 'PENGHASILAN_ATAS'),
            array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
	}
    function get_list_sekolah_datatable($request){
        $sql="select * from master_sekolah";
        $columns = array(
            0=>array('name' => 'SEKOLAH_ID'),
            1=>array('name' => 'SEKOLAH_KODE'),
            2=>array('name' => 'SEKOLAH_NAMA'),
            3=>array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
    }
    function get_list_usia_datatable($request){
        $sql="select * from master_kategori_usia";
        $columns = array(
            array('name' => 'KATEGORI_USIA_ID'),
            array('name' => 'KATEGORI_USIA_KODE'),
            array('name' => 'KATEGORI_USIA_NAMA'),
            array('name' => 'KATEGORI_USIA_BAWAH'),
            array('name' => 'KATEGORI_USIA_ATAS'),
            array('name' => 'TANGGAL_TRANSAKSI')
        );
        return $this->get_datatable($sql, $columns, $request);
    }
}