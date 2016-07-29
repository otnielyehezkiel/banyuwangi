<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/libraries/admin_controller.php';
class data extends admin_controller
{
    public $data = array('title' => 'data');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('datamodel');
        $this->load->library('session');
    }

//    fungsi-fungsi baru untuk bahan_makanan

    public function getkabupaten()
    {
        $this->db->select("id_kabupaten");
        $this->db->select("nama_kabupaten");
        $this->db->from('kabupaten');
        $query=$this->db->get();

        print json_encode($query->result_array());
    }

    public function getKecamatan($id_kabupaten)
    {
        $this->db->select("id_kecamatan");
        $this->db->select("nama_kecamatan");
        $this->db->where('id_kabupaten',$id_kabupaten);
        $this->db->from('kecamatan');
        $query=$this->db->get();

        print json_encode($query->result_array());
    }

    public function gettahun($table)
    {
        $query=$this->db->query("select EXTRACT(YEAR from waktu) as tahun_data from $table group by tahun_data order by tahun_data desc;");
        print json_encode($query->result_array());
    }

    public function set_jenis_session()
    {
        $value=$this->input->post('value');
        $this->load->library('session');
        $this->session->set_userdata('jenis_data',$value);
    }

    public function bahan_makanan($id_kabupaten=0,$id_kecamatan=0, $tahun_data=0)
    {
        if ($this->input->is_ajax_request()) {
            //field yanga akan di sum
            $sum=array('luas_panen','produktivitas','produksi');
            $jenis_data=$this->session->userdata('jenis_data');

            if($tahun_data==-1)
            {
                $this->datamodel->get_year_range_data('bahan_makanan',$id_kabupaten,$id_kecamatan,array('jenis_tanaman'=>$jenis_data),$sum);
            }
            else{
                $this->datamodel->loaddata('bahan_makanan',$id_kabupaten,$id_kecamatan, $tahun_data,array('jenis_tanaman'=>$jenis_data),$sum);
            }

            return;
        }

        $this->session->set_userdata('jenis_data',"Jagung");
        $col=array('Kabupaten', 'Kecamatan','Jenis Tanaman','Luas Panen','Produktivitas','Produksi','Tahun Data');
        $data['title']="Data Produksi Bahan Makanan";
        $data['head']=$col;
        $data['jenis_data']=$this->datamodel->getJenisData('bahan_makanan','jenis_tanaman');
        $data['table']='bahan_makanan';

        $this->load->view('view_data',$data);
    }

    public function coba()
    {
        $sum=array('luas_panen','produktivitas','produksi');
        //$this->datamodel->get_year_range_data('bahan_makanan',0,0,array('jenis_tanaman'=>"Padi Sawah"),$sum);
        $this->datamodel->loaddata('bahan_makanan',0,0, 0,array('jenis_tanaman'=>"Padi Sawah"),$sum);
    }
//    ---------------------------------------------------------------------------------------------------------------
    public function tambah($table)
    {
        $this->load->model('mdata');
        $this->mdata->insertData($table);
        redirect(site_url()."/data/viewdata/".$table);
    }


    public function saveFromExcel($table)
    {
        $this->load->model("excelmodel");
        $this->load->model("mdata");
        $filepath=$this->input->post('filepath');
        $checked_value=$this->input->post('row');
        $arr_data=$this->excelmodel->getExcelData($filepath);
        $waktu=date(date("Y-m-d",strtotime($this->input->post('waktu'))));
        $this->mdata->insertDataFromExcel($table,$arr_data['arr_data'],$checked_value,$waktu);
        redirect(site_url().'/upload_konsumsi');
    }

    public function saveFromExceltoKonsumsi()
    {
        $this->load->model("excelmodel");
        $this->load->model("mdata");
        $filepath = $this->input->post('filepath');
        $checked_value = $this->input->post('row');
        $id_tanaman = $this->input->post('tanaman');
        $arr_data = $this->excelmodel->getExcelData($filepath);
        $waktu=date(date("Y-m-d",strtotime($this->input->post('waktu'))));
        $this->mdata->insertKonsumsi($id_tanaman,$arr_data['arr_data'],$checked_value,$waktu);
        //redirect(site_url().'/upload_konsumsi');
    }

    public function viewdata($table)
    {
        $this->load->model("mdata");
        $query=$this->mdata->getData($table);
        $kabupaten=$this->db->query("SELECT * from kabupaten");
        $kecamatan=$this->db->query("SELECT * from kecamatan");
        $this->data["kabupaten"]=$kabupaten;
        $this->data["kecamatan"]=$kecamatan;
        $this->data["table_data"]=$query;
        $this->data["link"]=site_url()."/data/hapus/".$table."/";
        $this->data["link_edit"]=site_url()."/data/editdata/".$table."/";
        $this->data["table"]=$table;
        $this->load->view('view_data_pertanian',$this->data);
    }

    public function hapus($table,$id)
    {
        $query=$this->db->query("DELETE FROM $table WHERE id_bahan_makanan=$id");
        redirect(site_url()."/data/viewdata/".$table);
    }

    public function editdata($table,$id)
    {
        $this->load->model('mdata');
        if($this->input->post('submit'))
        {

            $this->mdata->updateData($table,$id);
            redirect(site_url()."/data/viewdata/".$table);
        }
        else
        {
            $table_data=$this->mdata->getDataById($table,$id);
            $this->data["table_data"]=$table_data;
            $this->data["link"]=site_url()."/data/editdata/".$table."/".$id;
            $this->load->view('view_pertanian_edit',$this->data);
        }

    }
}