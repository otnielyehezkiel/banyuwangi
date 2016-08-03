<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class api extends CI_Controller
{
    private function login($username,$password)
    {
        $this->db->select('password');
        $this->db->from('users_mobile');
        $this->db->where('username',$username);

        $query=$this->db->get();

        if($query->num_rows == 0)
        {
            return 2;
        }

        $res=$query->result();
        $hash=$res[0]->password;

        if (password_verify($password, $hash)) {
            return 1;
        } else {
            return 0;
        }

    }

    private function mapuser($id_user)
    {
        $query=$this->db->query("SELECT mr.nama_role, mr.id_role, um.nama, um.email, um.no_hp, um.alamat FROM users_mobile um, mobile_user_mapping mm, mobile_user_role mr where um.id_user=$id_user and mm.id_user=um.id_user and mr.id_role=mm.id_role;");
        $res=$query->result_array();

        return $res;
    }

    public function auth()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');

        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        $query=$this->db->query("SELECT id_user FROM users_mobile where username='$username'");
        $res=$query->result();
        $id_user=$res[0]->id_user;
        $map['auth']=$this->mapuser($id_user);

        print json_encode($map);
    }

    public function getdata($table)
    {

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $kode_kecamatan = $this->input->post('kecamatan');
        $waktu= $tahun.'-'.$bulan. '-01';

        $log = $this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }


        if($kode_kecamatan!=52500)
        {
            $query = $this->db->query("SELECT id_kecamatan from kecamatan where kode_kecamatan='$kode_kecamatan';");
            $query = $query->result();
            $id_kecamatan=$query[0]->id_kecamatan;
        }

        $arr_select=array("luas_panen","produktivitas","produksi");
        if($kode_kecamatan==52500)
        {
            $field=$this->db->list_fields($table);

            foreach ($arr_select as $val)
            {
                $this->db->select_sum($val);
            }
            $this->db->group_by('j.nama_tanaman');
            $this->db->select('j.nama_tanaman');
            $this->db->select($table.'.'.$field[0]);
            $this->db->select($table.'.'.$field[1]);
            $this->db->select($table.'.'.$field[2]);
            $this->db->select($table.'.'.$field[3]);
            $this->db->select('k.kode_kabupaten');
            $this->db->select('k.kode_kecamatan');
            $this->db->select('k.nama_kecamatan');
            $this->db->select('waktu');
            

        }
        else
        {
            $this->db->where("$table.id_kecamatan",$id_kecamatan);
        }

        $this->db->join('jenis_tanaman j','j.id_tanaman = '.$table.'.id_tanaman');
        $this->db->join('kecamatan k',"k.id_kecamatan = $table.id_kecamatan" );
        $this->db->where('waktu',$waktu);
        $this->db->from($table);

        $query=$this->db->get();

        if($query->num_rows()>0)
        {
            $data[$table] = $query->result_array();
            print json_encode($data);
        }
        else
        {
            print 0;
        }

    }

    public function getKecamatan()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');

        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        $this->db->select('kode_kecamatan');
        $this->db->select('nama_kecamatan');
        $this->db->from('kecamatan');
        $query=$this->db->get();

        if($query->num_rows()>0)
        {
            $data['kecamatan']=$query->result_array();
            $data['kecamatan'][]=
            print json_encode($data);
        }
        else
        {
            print 0;
        }
    }

    public function insertkegiatan()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $tanggal=$this->input->post('tanggal');
        $foto=$this->input->post('foto');
        $keterangan=$this->input->post('keterangan');

        $query=$this->db->query("SELECT id_user FROM users_mobile where username='$username'");
        $res=$query->result();
        $id_user=$res[0]->id_user;
        $log=$this->login($username,$password);
        $fotopath="http://198.71.80.189:8081/uploads/".$foto;
        if($log!=1)
        {
            print $log;
            return;
        }

        $auth=$this->mapuser($id_user);
        //var_dump($auth);
        if($auth[0]['nama_role']!="admin")
        {
            print '6';
            return;
        }

        $data=array(
            'foto'=>$fotopath,
            'id_user'=>$id_user,
            'tanggal'=>date("Y-m-d"),
            'keterangan'=>$keterangan
        );

        $this->db->insert('aktifitas_lapangan',$data);

        print 1;
    }

    function getkegiatan()
    {

    }

    /* Get All Data */
    public function getalldata()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);

        if($log != 1)
        {
            print $log;
            return;
        }

        $table = array('bahan_makanan','buah_buahan','sayur_sayuran','tanaman_perkebunan');
        $data = array();
        foreach ($table as $val)
        {
            $this->db->select('*');
            $this->db->from($val);
            $this->db->join('jenis_tanaman j','j.id_tanaman = '.$val.'.id_tanaman');
            $query = $this->db->get();
            $res=$query->result_array();
            $data[$val]=$res;
        }

        print json_encode($data);
    }

    public function getTahunProduksi(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $nama_tanaman = 0;
        if($this->input->post('tanaman') != null){
            $nama_tanaman = $this->input->post('tanaman');
        }
        $log = $this->login($username,$password);

        if($log != 1)
        {
            print $log;
            return;
        }

        $tanaman = array(
                'Padi Sawah',
                'Padi Ladang',
                'Jagung',
                'Kedelai',
                'Kacang Tanah',
                'Kacang Hijau',
                'Ubi Kayu',
                'Ubi Jalar'                
            );

        $hasil = array();
        // print_r($nama_tanaman); die();
        if($nama_tanaman){
            $query = $this->db->query("
                        SELECT EXTRACT(year FROM waktu) as tahun, SUM(produksi) as produksi_tahun 
                        FROM bahan_makanan b, jenis_tanaman j
                        where b.id_tanaman = j.id_tanaman and j.nama_tanaman = '".$nama_tanaman. "'
                        GROUP BY tahun
                    ");
            
            $hasil[$nama_tanaman] = $query->result();
        }
        else {
            foreach ($tanaman as $row) {
                $query = $this->db->query("
                        SELECT EXTRACT(year FROM waktu) as tahun, SUM(produksi) as produksi_tahun 
                        FROM bahan_makanan b, jenis_tanaman j
                        where b.id_tanaman = j.id_tanaman and j.nama_tanaman = '".$row. "'
                        GROUP BY tahun
                    ");
                $hasil[$row] = $query->result();
            }
        }
        

        $res['gettahunproduksi'] = $hasil;

        echo json_encode($res);
    }

    public function detailGetTahunProduksi(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $nama_tanaman = 0;
        if($this->input->post('tanaman') != null){
            $nama_tanaman = $this->input->post('tanaman');
        }
        $log = $this->login($username,$password);

        if($log != 1)
        {
            print $log;
            return;
        }
    }

    public function registrasi(){}

    public function getallkegiatan()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        $query=$this->db->query("SELECT al.foto, al.keterangan,al.tanggal, um.username 
                                from aktifitas_lapangan al, users_mobile um 
                                where um.id_user=al.id_user
                                order by al.tanggal DESC;
                            ");

        $data["kegiatan"]=$query->result_array();

        print json_encode($data);
    }

    public function getketersedian()
    {
        $table="ketersedian";
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $bulan=$this->input->post('bulan');
        $tahun=$this->input->post('tahun');
        $kode_kecamatan=$this->input->post('kecamatan');
        $waktu= $tahun.'-'.$bulan. '-01';
        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }


        if($kode_kecamatan!=52500)
        {
            $query=$this->db->query("SELECT id_kecamatan from kecamatan where kode_kecamatan='$kode_kecamatan';");
            $query=$query->result();
            $id_kecamatan=$query[0]->id_kecamatan;
        }

        $arr_select=array("jumlah_penduduk","luas_panen","provitas","produksi_padi","konversi_beras","bibit","pakan","tercecer","ketersedian_beras","kebutuhan_konsumsi_riil","perimbangan","rasio_ketersediaan");
        if($kode_kecamatan==52500)
        {
            foreach ($arr_select as $val)
            {
                $this->db->select_sum($val);
            }
            $this->db->group_by('jenis_tanaman');
            $this->db->select('nama_kecamatan');
            $this->db->select('waktu');
        }
        else
        {
            $this->db->where("$table.id_kecamatan",$id_kecamatan);
        }
        $this->db->join('kecamatan',"kecamatan.id_kecamatan=$table.id_kecamatan");
        $this->db->where('waktu',$waktu);
        $this->db->from($table);

        $query=$this->db->get();

        if($query->num_rows()>0)
        {
            $data[$table]=$query->result_array();
            print json_encode($data);
        }
        else
        {
            print 0;
        }
    }

    /*Get data pasar di banyuwangi*/
    public function getPasar()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        $url = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getMasterMarket";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $res = json_decode($data,true);
        $pasar = array();
        $pasar['getpasar'] = array();
        if($res['success'] == 1){
            foreach ($res['result'] as $row) {
                if($row['kabkota_id'] == 6){ // id kabupaten banyuwangi = 6
                    $pasar['getpasar'][] = array(
                        'id' => $row['market_id'],
                        'nama' => $row['market_name']
                    );
                }
            }
            echo json_encode($pasar);
        }
    }

    /*Get data komoditas*/
    public function getKomoditas()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        $url = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getMasterCommodity";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $res = json_decode($data);
        if($res->success == 1){
            $hasil['getkomoditas'] = $res->result;
            echo json_encode($hasil);
        }
    }

     /*Get data harga from api*/
    public function getHarga()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        $tanggal = $this->input->post('tanggal');
        $id_pasar = $this->input->post('id_pasar');
        $url = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getDailyPriceAllMarket&tanggal=".$tanggal;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $res = json_decode($data,true);

        $url2 = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getMasterCommodity";
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $url2);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        $data2 = curl_exec($ch2);
        $res2 = json_decode($data2,true);
        $komoditas = $res2['result'];

        $kom = array();
        foreach($komoditas as $row){
            $id = $row['commodity_id'];
            $kom[$id] = array(
                'commodity_name' => $row['commodity_name'],
                'commodity_unit' => $row['commodity_unit'],
                'commodity_title' => $row['commodity_title']
                ); 
        }

        $prev_date = date('Y-m-d', strtotime($tanggal .' -1 day'));

        $url3 = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getDailyPriceAllMarket&tanggal=".$prev_date;
        $ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL, $url3);
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
        $data3 = curl_exec($ch3);
        $res3 = json_decode($data3,true);

        $harga = array();
        $harga['getharga'] = array();
        $today = array();
        $yesterday = array();

        if($res['success']==1 && $res3['success']==1){
            foreach($res['result'] as $row){
                if($row['market_id'] == $id_pasar){
                    $harga['getharga'] = $row['details'];
                }
            }
            foreach($res3['result'] as $row1){
                if($row1['market_id'] == $id_pasar){
                    $kemarin = $row1['details'] ;
                }
            }
            $price_yes = array_column($kemarin, 'price', 'commodity_id');

            foreach($harga['getharga'] as &$row) {
                $id = $row['commodity_id'];
                $row = array_merge($row, $kom[$id]);
                $row = array_merge($row, array('price_yesterday' =>  $price_yes[$id]) );
            }

            echo json_encode($harga);
        }
    }

    /* get Detail Ketersediaan dari jenis tanaman di seluruh kecamatan*/
    public function getDetailKetersediaan()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $id_kecamatan = $this->input->post('id_kecamatan');
        $log=$this->login($username,$password);
        if($log!=1)
        {
            print $log;
            return;
        }
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $waktu = $tahun.'-'.$bulan. '-01';

        $arr_select=array("nama_tanaman","jumlah_penduduk","luas_panen","provitas","produksi_padi","konversi_beras","bibit","pakan","tercecer","ketersediaan_beras","kebutuhan_konsumsi_riil","perimbangan","rasio_ketersediaan");
        $this->db->select($arr_select);
        $this->db->from('ketersediaan k');
        $this->db->join('jenis_tanaman j', 'j.id_tanaman = k.id_tanaman');
        $this->db->join('kecamatan kc', 'kc.id_kecamatan = k.id_kecamatan');
        $this->db->where('waktu',$waktu);
        $this->db->where('kc.kode_kecamatan',$id_kecamatan);

        $query = $this->db->get();
        $rows = $query->result_array();
        /*  
            : Ratio > 1,14 = surplus    
            : Ratio 1,00 - 1,14 = Swasembada    
            : Ratio 0,95 - 1,00 = Cukup 
            : Ratio < 0,95 = Defisit    
        */
        if($rows == null){
            $result['getdetailketersediaan'] = 'Data Kosong';
            echo json_encode($result);
            return;
        }
        $i = 0;
        foreach($rows as $row){
            if($row['rasio_ketersediaan'] > 1.14) 
                $result['getdetailketersediaan'][$i] =  array_merge($row, array('ratio'=>'Surplus'));
            elseif($row['rasio_ketersediaan'] > 1.00)
                $result['getdetailketersediaan'][$i] =   array_merge($row, array('ratio'=>'Swasembada'));
            elseif($row['rasio_ketersediaan'] > 0.95)
                $result['getdetailketersediaan'][$i] =   array_merge($row, array('ratio'=>'Cukup'));
            else 
                $result['getdetailketersediaan'][$i] = array_merge($row, array('ratio'=>'Defisit'));
            $i++;
        }
        

        echo json_encode($result);
    }

    /* get Ketersediaan semua tanaman di seluruh kecamatan*/
    public function getAllKetersediaan()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);
        if($log!=1)
        {
            print $log;
            return;
        }

        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $waktu = $tahun.'-'.$bulan. '-01';

        $arr_select=array("nama_tanaman","jumlah_penduduk","luas_panen","provitas","produksi_padi","konversi_beras","bibit","pakan","tercecer","ketersediaan_beras","kebutuhan_konsumsi_riil","perimbangan","rasio_ketersediaan");
        $this->db->select($arr_select);
        $this->db->from('ketersediaan k');
        $this->db->join('jenis_tanaman j', 'j.id_tanaman = k.id_tanaman');
        $this->db->where('waktu',$waktu);
        $this->db->where('id_kecamatan',25);

        $query = $this->db->get();
        $rows['getallketersediaan'] = $query->result_array();
        echo json_encode($rows);
    }





}