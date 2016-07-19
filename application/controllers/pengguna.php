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
 require_once APPPATH . '/libraries/admin_controller.php';
 //extends CI Controller tidak extends admin_controller karena menghindari loop redirect
class pengguna extends admin_controller {

    public $data = array('title' => 'Management Pengguna');

    public function __construct() {
        parent::__construct();
		
    }
    
    function edit(){
        $session=$this->session->userdata('jombang_session');
        $nama=$this->input->post('nama');
        $username=$this->input->post('username');
        $password_lama=$this->input->post('password0');
        $password_baru1=$this->input->post('password1');
        $password_baru2=$this->input->post('password2');
        $sql="update pengguna set PENGGUNA_NAMA=?, PENGGUNA_USERNAME=? ";
        if(strlen($password_lama)>0 || strlen($password_baru1)>0){
            //update password
            if(md5($password_lama)==$session['PENGGUNA_PASSWORD']){
                if($password_baru1==$password_baru2){
                    $sql .= ", PENGGUNA_PASSWORD=? WHERE PENGGUNA_ID=?";
                    $this->db->query($sql,array($nama,$username,md5($password_baru1),$session['PENGGUNA_ID']));
                    echo "profile dan password berhasil diupdate";
                }else{
                    echo "password baru yang anda masukkan harus sama di kedua isian";
                }

            }else{
                echo "Password Lama yang anda masukkan tidak cocok";
            }
        }else{
            //bukan update password
            $sql.=" where PENGGUNA_ID=?";
            $this->db->query($sql,array($nama,$username,$session['PENGGUNA_ID']));
            echo "profile berhasil diupdate";
        }
        $q=$this->db->query("select * from pengguna where PENGGUNA_ID=?",array($session['PENGGUNA_ID']))->result_array();
        if(count($q)>0){
            $this->session->set_userdata('jombang_session',$q[0]);
        }
        header('refresh:3;url='.site_url().'/pengguna/profile');
    }
		
	function get_list_pengguna(){
		if(in_array("view_user", $this->my_roles) == false){
			$this->load->view("page/error",array("pesan"=>"Anda tidak berhak mengakses fungsionalitas ini"));
			return;
		}
		$this->load->model(array("pengguna_model"));
		$session = $this->my_session;
		$list_pengguna = $this->pengguna_model->get_list_pengguna($session, $_POST);
		echo json_encode($list_pengguna);
	}
	
	function manage(){
		if(in_array("view_user", $this->my_roles) == false){
			$this->load->view("page/error",array("pesan"=>"Anda tidak berhak mengakses fungsionalitas ini"));
			return;
		}
		$this->load->view("pengguna/view_manage", $this->data);
	}
	
	function mapping_role(){
		if(in_array("edit_user", $this->my_roles) == false){
			$this->load->view("page/error",array("pesan"=>"Anda tidak berhak mengakses fungsionalitas ini"));
			return;
		}
		$id_pengguna = intval($this->input->get("id_pengguna"));
		$q = $this->db->where(array("PENGGUNA_ID" => $id_pengguna))->get("pengguna")->result_array();
		if(count($q)<1){
			$this->load->view("page/error",array("pesan"=>"Pengguna dengan ID tidak dapat ditemukan"));
			return;
		}
		$pengguna = $q[0];
		$session = $this->my_session;
		if($session["PENGGUNA_ID"] == $pengguna["PENGGUNA_ID"]){
			$this->load->view("page/error",array("pesan"=>"anda tidak dapat mengubah role anda"));
			return;
		}
		$role_pengguna = $this->db->where(array("PENGGUNA_ID"=>$id_pengguna))->get("map_role_pengguna")->result_array();
		$available_role = $this->db->get("role")->result_array();
		$this->data["pengguna"] = $pengguna;
		$this->data["role_pengguna"]=$role_pengguna;
		$this->data["role"]=$available_role;
		$this->data["title"] = "Management Role Pengguna";
		$this->data["id_pengguna"] = $id_pengguna;
		$this->load->view("pengguna/view_role_pengguna",$this->data);
	}
	
	function profile(){
		//print_r($this->my_session);
        //$session=$this->session->userdata('jombang_session');
        $this->data['profile']=$this->my_session;
        $this->load->view('pengguna/view_profile',$this->data);
    }
	
	function tambah_pengguna_json(){
		$res = $this->tambah_pengguna();
		echo json_encode($res);
	}
	
	private function tambah_pengguna(){
		$hasil = array("status"=>"failed","reason"=>"Anda tidak berhak mengakses fungsionalitas ini");
		if(in_array("add_user", $this->my_roles) == false){
			return $hasil;
		}
		$nama = $this->input->post("nama");
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$q = $this->db->where(array("PENGGUNA_USERNAME"=>$username))->get("pengguna")->result_array();
		if(count($q)>0){
			$hasil["reason"]="username telah terpakai, pilih username yang lain";
			return $hasil;
		}
		$ins = $this->db->insert("pengguna", array("PENGGUNA_NAMA"=>$nama,"PENGGUNA_USERNAME"=>$username,"PENGGUNA_PASSWORD"=>md5($password)));
		if($ins==1){
			$hasil["status"]="ok";
		}else{
			$hasil["reason"]="gagal mendaftarkan user baru ke database";
		}
		return $hasil;
	}
	
	private function update_pengguna(){
		$id_pengguna = intval($this->input->post("id_pengguna"));
		$nama = $this->input->post("nama");
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$hasil = array("status"=>"fail","reason"=>"Anda tidak berhak mengakses fungsionalitas ini");
		if(in_array("edit_user", $this->my_roles) == false){
			return $hasil;
		}
		$q = $this->db->where(array("PENGGUNA_ID"=>$id_pengguna))->get("pengguna")->result_array();
		if(count($q)<1){
			$hasil["reason"]="data pengguna tidak dapat ditemukan";
			return $hasil;
		}
		$q = $this->db->where(array("PENGGUNA_USERNAME" => $username))->get("pengguna")->result_array();
		if(count($q)>1){
			$hasil["reason"]="Username telah terpakai, pilih username yang lain";
			return $hasil;
		}else if(count($q)>0){
			$pengguna = $q[0];
			if($pengguna["PENGGUNA_ID"] != $id_pengguna){
				$hasil["reason"]="Username telah terpakai, pilih username yang lain";
				return $hasil;
			}
		}
		$update = array("PENGGUNA_USERNAME"=>$username, "PENGGUNA_NAMA" => $nama);
		if(strlen($password)>0){
			$update["PENGGUNA_PASSWORD"] = md5($password);
		}
		$upd = $this->db->update("pengguna", $update, array("PENGGUNA_ID"=>$id_pengguna));
		if($upd==1){
			$hasil["status"]="ok";
		}else{
			$hasil["reason"] = "Gagal mengupdate data pengguna";
		}
		return $hasil;
	}
	
	function update_pengguna_json(){
		$hasil = $this->update_pengguna();
		echo json_encode($hasil);
	}
	
	function update_role_pengguna(){
		if(in_array("edit_user", $this->my_roles) == false){
			$this->load->view("page/error",array("pesan"=>"Anda tidak berhak mengakses fungsionalitas ini"));
			return;
		}
		$id_pengguna = intval($this->input->post("id_pengguna"));
		$q = $this->db->where(array("PENGGUNA_ID" => $id_pengguna))->get("pengguna")->result_array();
		if(count($q) < 1){
			$this->load->view("page/error",array("pesan"=>"Pengguna dengan ID tidak dapat ditemukan"));
			return;
		}
		$pengguna = $q[0];
		$session = $this->my_session;
		if($session["PENGGUNA_ID"] == $pengguna["PENGGUNA_ID"]){
			$this->load->view("page/error",array("pesan"=>"Anda tidak dapat mengubah sendiri role anda"));
			return;
		}
		$role_baru = $this->input->post("check_role");
		if(is_array($role_baru)){
			$q = $this->db->get("role")->result_array();
			$available_role = array();
			foreach($q as $r){
				$available_role[] = $r["ROLE_ID"];
			}
			$q = $this->db->where(array("PENGGUNA_ID" => $id_pengguna))->get("map_role_pengguna")->result_array();
			$old_role = array();
			foreach($q as $old){
				$old_role[] = $old["ROLE_ID"];
			}
			$new_role = array();
			foreach($role_baru as $nr){
				$nr = intval($nr);
				if(in_array($nr, $available_role)){
					if(in_array($nr, $old_role) == false){
						$this->db->insert("map_role_pengguna", array("PENGGUNA_ID" => $id_pengguna, "ROLE_ID" => $nr));
					}
					$new_role[] = $nr;
				}
			}
			foreach($available_role as $r){
				if(in_array($r, $new_role) == false){
					$this->db->delete("map_role_pengguna", array("PENGGUNA_ID" => $id_pengguna, "ROLE_ID" => $r));
				}
			}
			print_r($role_baru);
			print_r($available_role);
			print_r($old_role);
			print_r($new_role);
		}
		
		redirect(site_url().'/pengguna/manage');
		//$this->load->view("pengguna/view_role_pengguna_close");
	}
}