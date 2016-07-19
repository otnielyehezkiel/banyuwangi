<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of desa
 *
 * @author mozar
 */
 //require_once APPPATH . '/libraries/admin_controller.php';
 //extends CI Controller tidak extends admin_controller karena menghindari loop redirect
class login extends CI_Controller {

    public $data = array('title' => 'Master Desa');

    public function __construct() {
        parent::__construct();
		$this->load->library(array("session"));
    }
	
    function halaman_login(){
        $this->load->view('pengguna/view_login',$this->data);
    }
    
    function logout(){
        $this->session->unset_userdata('jombang_session');
        redirect(site_url().'/pengguna/halaman_login');
    }
    function do_login(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        //echo md5('12345678');
        $q=$this->db->query("select * from pengguna where PENGGUNA_USERNAME=?",array($username))->result_array();
        if(count($q)>0){
            $pengguna=$q[0];
            if($pengguna['PENGGUNA_PASSWORD']==md5($password)){
                $this->load->library('session');
                $this->session->set_userdata('jombang_session',$pengguna);
                redirect(site_url()."/data/viewdata/bahan_makanan");
            }else{
                redirect(site_url().'/pengguna/halaman_login');
            }

        }else{
            redirect(site_url().'/pengguna/halaman_login');
        }
    }
	
}