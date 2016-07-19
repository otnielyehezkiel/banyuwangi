<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_controller
 *
 * @author mozar
 */
class admin_controller extends CI_Controller {
	public $my_session = null;
	public $my_roles = array();
	public $data = array("title" => "Data PBI Jombang");
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
		$this->my_session = $this->session->userdata('jombang_session');
        if($this->my_session==null){
        	redirect(site_url().'/login/halaman_login');
        	exit(0);
        }
		//print_r($this->my_session);
		$my_id = $this->my_session["PENGGUNA_ID"];
		$q = $this->db->query("select role.* from role inner join map_role_pengguna on role.ROLE_ID = map_role_pengguna.ROLE_ID where map_role_pengguna.PENGGUNA_ID='$my_id'")->result_array();
		foreach($q as $role){
			$this->my_roles[] = $role["ROLE_CODE"];
		}
    }

}
