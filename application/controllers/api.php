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
        $this->db->where('status', 1);

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

    public function profile()
    {
        $username=$this->input->post('username');
        $password=$this->input->post('password');

        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        $query = $this->db->query("SELECT nama, email, alamat, no_hp FROM users_mobile where username='$username'");
        $res = $query->result();
        $hasil['profile'] = $res;
        echo json_encode($hasil);
    }

    /*Api untuk registrasi akun*/
    public function registrasi()
    {
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $nama = $this->input->post('nama');
        $no_hp = $this->input->post('no_hp');
        $alamat = $this->input->post('alamat');
        $password = $this->input->post('password');
        $role = 1; //default role 'admin', jika role 'user' maka $role = 2.
        if($this->input->post('role')){
            $role = $this->input->post('role');
        }
        $query = $this->db->query('select * from users_mobile where username="'.$username.'" or email="'.$email.'"');

        if($query->result()){
            $hasil['registrasi'] = 'Username atau Email sudah digunakan'; 
            echo json_encode($hasil);
        }
        else {
            $arr = array(
                    'username' => $username,
                    'nama' => $nama,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'alamat' => $alamat,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'status' => 0  // status = 0 maka user blm diverifikasi
                );

            /* Sent email to user*/
            $config['smtp_host'] = 'ssl://smtp.googlemail.com';
            $config['protocol'] = 'smtp';
            $config['mailtype'] = 'html';
            $config['smtp_user'] = 'cs.ngooyakk@gmail.com';
            $config['smtp_pass'] = 'ngooyakkmotor';
            $config['smtp_port'] = 465;
            $this->load->library('email', $config);        
            $this->email->set_newline("\r\n");

            $this->email->from('cs.ngooyakk@gmail.com', 'banyuwangi apps');
            $this->email->to($email);
            $this->email->subject('Verifikasi Email'); 
            $this->email->message( "Terimakasih Telah Mendaftar,".$nama.".
                    Silahkan klik link dibawah ini untuk melakukan verifikasi email
                    <a href='".site_url('api/verify')."/".md5($email)."'> Klik Disini </a>
                "); 

            $this->db->insert('users_mobile',$arr);
            $id_res = $this->db->insert_id();
            $res2 = $this->db->insert('mobile_user_mapping',
                        array(
                            'id_user' => $id_res,
                            'id_role' => $role)
                    );
            if($res2 && $this->email->send()){
                $hasil['registrasi'] = 'Berhasil, Silahkan Verifikasi Email';
                echo json_encode($hasil);
            } else {
                $hasil['registrasi'] = 'Gagal';
                echo json_encode($hasil);
            }
        }
    }

    public function editProfile(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);
        if($log != 1)
        {
            print $log;
            return;
        }
        $nama = $this->input->post('nama');
        $no_hp = $this->input->post('no_hp');
        $alamat = $this->input->post('alamat');
        $email = $this->input->post('email');

        $data = array(
                'nama' => $nama,
                'no_hp' => $no_hp,
                'alamat' => $alamat,
                'email' => $email
            );

        $this->db->where('username', $username);
        $query = $this->db->update('users_mobile', $data);

        if($query){
            $hasil['editProfile'] = 'Berhasil';
            echo json_encode($hasil);
        }
        else {
            $hasil['editProfile'] = 'Gagal';
            echo json_encode($hasil);
        }


    }

    public function verify($key)
    {
        $this->db->select('*');
        $this->db->from('users_mobile');
        $this->db->where('md5(email)',$key);
        $query = $this->db->get();
        if($query->result()){
            $this->db->where('md5(email)',$key);
            $q_update = $this->db->update('users_mobile',array('status'=>1));
            echo "Verifikasi Berhasil";
        } else {
            echo "Verifikasi Gagal!";
        }
        

    }
    /*Mengganti Password*/
    public function changePassword(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $password_baru = $this->input->post('password_baru');

        $log = $this->login($username, $password);

        if($log!=1)
        {
            $hasil['changepass'] = 'Gagal';
            echo json_encode($hasil);
            return;
        }

        
        $data = array(
                'password' => password_hash($password_baru, PASSWORD_DEFAULT)
            );
        $this->db->where('username', $username);
        $query = $this->db->update('users_mobile', $data);

        if($query){
            $hasil['changepass'] = 'Berhasil';
            echo json_encode($hasil);
        }
        else {
            $hasil['changepass'] = 'Gagal';
            echo json_encode($hasil);
        }
    }

    function get_random_password($chars_min=6, $chars_max=8, $use_upper_case=false, $include_numbers=false, $include_special_chars=false)
    {
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
        if($include_numbers) {
            $selection .= "1234567890";
        }
        if($include_special_chars) {
            $selection .= "!@\"#$%&[]{}?|";
        }

        $password = "";
        for($i=0; $i<$length; $i++) {
            $current_letter = $use_upper_case ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];            
            $password .=  $current_letter;
        }                

      return $password;
    }

    public function forgotPass(){
        
        $username = $this->input->post('username');
        $email = $this->input->post('email');

        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['wordwrap'] = TRUE;
        $config['smtp_user'] = 'cs.ngooyakk@gmail.com';
        $config['smtp_pass'] = 'ngooyakkmotor';
        $config['smtp_port'] = 465;
        $this->load->library('email', $config);        
        $this->email->set_newline("\r\n");

        $check = $this->db->query('select * from users_mobile where username="'.$username.'" and email="'.$email.'" ');

        if(!$check->result()){
            $res['forgotpass'] = 'Username atau Email tidak terdaftar';
            echo json_encode($res);
            return;
        }

        $new_pass = $this->get_random_password();
        $data = array('password' => password_hash($new_pass, PASSWORD_DEFAULT));
        $this->db->where('username', $username);
        $query = $this->db->update('users_mobile', $data);

        $this->email->from('cs.ngooyakk@gmail.com', 'banyuwangi apps');
        $this->email->to($email);
        $this->email->subject('Lupa Password'); 
        $this->email->message('Password baru anda sekarang adalah: '.$new_pass.'<br> <i> Pastikan Anda mengganti password Anda setelah login. <i>'); 

        if($this->email->send() && $query){
            $res['forgotpass'] = 'Berhasil';
            echo json_encode($res);
        }
        else {
            $res['forgotpass'] = 'Gagal';
            echo json_encode($res);
        }


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
            
            $hasil = $query->result();
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
        $this->load->model('pasarmodel');
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        if($this->pasarmodel->getData('pasar')){
            $result['getpasar'] = $this->pasarmodel->getData('pasar');
            echo json_encode($result);
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
                if($row['kabkota_id'] == 6 || $row['kabkota_id'] == 1){ // id kabupaten banyuwangi = 6
                    $pasar['getpasar'][] = array(
                        'id' => $row['market_id'],
                        'nama' => $row['market_name']
                    );
                }
            }
            $result = $this->pasarmodel->insertData('pasar',$pasar['getpasar']);
            if(!$result) echo "Insert Gagal!";
            echo json_encode($pasar);
        }
    }

    /*Get data komoditas*/
    public function getKomoditas()
    {
        $this->load->model('pasarmodel');
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);

        if($log!=1)
        {
            print $log;
            return;
        }

        if($this->pasarmodel->getData('commodity')){
            $result['getkomoditas'] = $this->pasarmodel->getData('commodity');
            echo json_encode($result);
            return;
        }
        $url = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getMasterCommodity";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $res = json_decode($data,true);
        if($res['success'] == 1){
            $hasil['getkomoditas'] = $res['result'];

            $result = $this->pasarmodel->insertData('commodity',$hasil['getkomoditas']);
            if(!$result) echo "Insert Gagal!";
            echo json_encode($hasil);
        }
    }

     /*Get data harga from api*/
    public function getHarga()
    {
        $this->load->model('pasarmodel');
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

        if($this->pasarmodel->isInserted($tanggal, $id_pasar)){
            $harga['getharga'] = $this->pasarmodel->isInserted($tanggal, $id_pasar);
            echo json_encode($harga);
            return;
        }

        $url = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getDailyPriceAllMarket&tanggal=".$tanggal;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $res = json_decode($data,true);

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

        if($res['success'] == 1 && $res3['success'] == 1){
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
                $row = array_merge($row, array('price_yesterday' =>  $price_yes[$id]) );
            }
            // echo json_encode($harga);

            $result = $this->pasarmodel->insertPasar($tanggal, $id_pasar, $harga['getharga']);
            if(!$result) echo "Insert Gagal!";
            $hasil['getharga'] = $this->pasarmodel->isInserted($tanggal, $id_pasar);
            echo json_encode($hasil);
        }
    }

    public function getPasarWonokromo()
    {
        $this->load->model('pasarmodel');

        $tanggal ='2016-09-29';
        $id_pasar = 2;

        if($this->pasarmodel->isInserted($tanggal, $id_pasar)){
            $harga['getharga'] = $this->pasarmodel->isInserted($tanggal, $id_pasar);
            echo json_encode($harga);
            return;
        }


        $url = "http://siskaperbapo.com/api/?username=pihpsapi&password=xxhargapanganxx&task=getDailyPriceAllMarket&tanggal=".$tanggal;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        $res = json_decode($data,true);

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

        if($res['success'] == 1 && $res3['success'] == 1){
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
                $row = array_merge($row, array('price_yesterday' =>  $price_yes[$id]) );
            }

            $result = $this->pasarmodel->insertPasar($tanggal, $id_pasar, $harga['getharga']);
            if(!$result) echo "Insert Gagal!";
            $hasil['getharga'] = $this->pasarmodel->isInserted($tanggal, $id_pasar);
            echo json_encode($hasil);
        }
    }

    public function getPasarWonokromo7Hari()
    {
        $this->load->model('pasarmodel');

        $tanggal = '2016-09-29';
        $id_pasar = 2;
        $harga['getharga'] = $this->pasarmodel->wonokromo($tanggal, $id_pasar);
        echo json_encode($harga);            
        
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

    public function getAllPost(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);
        if($log!=1)
        {
            print $log;
            return;
        }

        $this->db->select('p.*, u.nama, count(c.id_post) as total_comment');
        $this->db->from('post_mobile p');
        $this->db->join('users_mobile u', 'u.id_user = p.id_user');
        $this->db->join('comment_mobile c','c.id_post = p.id_post');
        $this->db->where('p.status',0);
        $this->db->group_by('c.id_post');
        $this->db->order_by('created_at', 'DESC');

        $query = $this->db->get();
        $rows['getallpost'] = $query->result_array();
        echo json_encode($rows);

    }

    public function getPost(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);
        if($log!=1)
        {
            print $log;
            return;
        }
        $id_post=$this->input->post('id_post');

        $this->db->select('p.*, u.nama');
        $this->db->from('post_mobile p');
        $this->db->join('users_mobile u', 'u.id_user = p.id_user');
        $this->db->where('p.id_post', $id_post);

        $query = $this->db->get();
        $rows['getpost'] = $query->result_array();
        echo json_encode($rows);
    }

    public function getComment(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $log=$this->login($username,$password);
        if($log!=1)
        {
            print $log;
            return;
        }
        $id_post = $this->input->post('id_post');

        $this->db->select('c.*, u.nama');
        $this->db->from('comment_mobile c');
        $this->db->join('users_mobile u', 'c.id_user = u.id_user');
        $this->db->where('c.id_post', $id_post);
        $this->db->where('c.status', 0);
        $this->db->order_by('created_at', 'DESC');
        
        $query = $this->db->get();
        $rows['getcomment'] = $query->result_array();
        echo json_encode($rows);
    }

    public function addPost(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);
        if($log != 1)
        {
            print $log;
            return;
        }
        $isi = $this->input->post('isi');
        $query = $this->db->query("SELECT id_user FROM users_mobile where username='$username'");
        $res = $query->result();
        $id_user = $res[0]->id_user;

        $data = array(
            'id_user' => $id_user,
            'created_at' => date('Y-m-d H:i:s'),
            'isi' => $isi,
            'status' => 0
        );

        $res = $this->db->insert('post_mobile', $data);
        if($res) {
            $rows['addpost'] = "Berhasil"; 
            echo json_encode($rows);
        } else {
            $rows['addpost'] = "Gagal"; 
            echo json_encode($rows);
        }
    }

    public function addComment(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);
        if($log != 1)
        {
            print $log;
            return;
        }

        $isi = $this->input->post('isi');
        $query = $this->db->query("SELECT id_user FROM users_mobile where username='$username'");
        $res = $query->result();
        $id_user = $res[0]->id_user;
        $id_post = $this->input->post('id_post');
        $data = array(
            'id_user' => $id_user,
            'id_post' => $id_post,
            'created_at' => date('Y-m-d H:i:s'),
            'isi' => $isi,
            'status' => 0
        );

        $res = $this->db->insert('comment_mobile', $data);
        if($res) {
            $rows['addcomment'] = "Berhasil"; 
            echo json_encode($rows);
        } else {
            $rows['addcomment'] = "Gagal"; 
            echo json_encode($rows);
        }
    }

    public function deleteComment(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);
        if($log != 1)
        {
            print $log;
            return;
        }
        $id_comment = $this->input->post('id_comment');
        $this->db->where('id_comment',$id_comment);
        $delete = $this->db->update('comment_mobile',array('status'=>1));

        if($delete){
            $rows['deletecomment'] = "Berhasil"; 
            echo json_encode($rows);
        } else {
            $rows['deletecomment'] = "Gagal"; 
            echo json_encode($rows);
        }
    }

    public function deletePost(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);
        if($log != 1){
            print $log;
            return;
        }
        $id_post = $this->input->post('id_post');
        $this->db->where('id_post',$id_post);
        $delete = $this->db->update('post_mobile', array('status'=>1));

        if($delete){
            $rows['deletepost'] = "Berhasil"; 
            echo json_encode($rows);
        } else {
            $rows['deletepost'] = "Gagal"; 
            echo json_encode($rows);
        }
    }

    public function editPost(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);
        if($log != 1){
            print $log;
            return;
        }
        $id_post = $this->input->post('id_post');
        $isi = $this->input->post('isi');
        $this->db->where('id_post',$id_post);
        $delete = $this->db->update('post_mobile',  
                        array(
                            'isi' => $isi,
                            'created_at' => date('Y-m-d H:i:s')
                        ));

        if($delete){
            $rows['editpost'] = "Berhasil"; 
            echo json_encode($rows);
        } else {
            $rows['editpost'] = "Gagal"; 
            echo json_encode($rows);
        }
    }

    public function editComment(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $log = $this->login($username,$password);
        if($log != 1){
            print $log;
            return;
        }
        $id_comment = $this->input->post('id_comment');
        $isi = $this->input->post('isi');
        $this->db->where('id_comment',$id_comment);
        $delete = $this->db->update('comment_mobile',  
                        array(
                            'isi' => $isi,
                            'created_at' => date('Y-m-d H:i:s')
                        ));

        if($delete){
            $rows['editcomment'] = "Berhasil"; 
            echo json_encode($rows);
        } else {
            $rows['editcomment'] = "Gagal"; 
            echo json_encode($rows);
        }
    }

}