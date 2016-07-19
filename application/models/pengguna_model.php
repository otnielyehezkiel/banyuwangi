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
 * Description of kabupaten_model
 *
 * @author mozar
 */
class pengguna_model extends datatable {
	
	function get_list_pengguna($session = array(), $request = array()){
		$my_id = intval($session["PENGGUNA_ID"]);
		$sql = "select pengguna.*, now() as sekarang
			from pengguna where pengguna.PENGGUNA_ID != '$my_id'
			";
		$columns = array(
            0=>array('name' => 'PENGGUNA_ID'),
            1=>array('name' => 'PENGGUNA_NAMA'),
            2=>array('name' => 'PENGGUNA_USERNAME'),
            3=>array('name' => 'sekarang')
        );
        return $this->get_datatable($sql, $columns, $request);
	}
	
}
