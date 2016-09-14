<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mdata extends CI_MODEL
{

    public function insertData($table)
    {
        $data= array(
          'jenis_tanaman' =>$this->input->post('jenis_tanaman'),
            'luas_panen' => $this->input->post('luas_panen'),
            'produktivitas' => $this->input->post('produktivitas'),
            'produksi' => $this->input->post('produksi'),
            'waktu' => date("Y-m-d",strtotime($this->input->post('waktu')))
        );

        $this->db->insert($table,$data);
    }

    public function insertDataFromExcel($table, $arr_data, $arr_check, $waktu)
    {
        $cek = array_filter($arr_check);

        if (empty($cek)) {
            return;
        }
        foreach ($arr_check as $index)
        {
            $index+=1;
            $val= $arr_data[$index];
            $cekada=$this->db->query("SELECT * from $table WHERE id_kecamatan='$val[3]' and jenis_tanaman='$val[5]' and waktu='$waktu'");

            $val5=str_replace(',','.',$val[6]);
            $val6=str_replace(',','.',$val[7]);
            $val7=str_replace(',','.',$val[8]);
            if($cekada->num_rows()==0)
            {
                $query=$this->db->query("INSERT INTO $table VALUES(null, $val[2], $val[3], '$val[5]', $val5, $val6, $val7, '$waktu');");
            }
            else
            {
                $query=$this->db->query("UPDATE bahan_makanan SET luas_panen=$val5,produktivitas=$val6,produksi=$val7 WHERE jenis_tanaman='$val[2]' and waktu='$waktu';");
            }
        }
    }

    public function insertProduksi($table, $arr_data, $arr_check, $waktu,$id_kecamatan)
    {
        $cek = array_filter($arr_check);

        if (empty($cek)) {
            return;
        }

        foreach ($arr_check as $index)
        {
            $index+=1;
            $val= $arr_data[$index];
            $cekada=$this->db->query("SELECT * from $table WHERE id_kecamatan='$id_kecamatan' and id_tanaman='$val[2]' and waktu='$waktu'");

            $val5=str_replace(',','.',$val[4]);
            $val6=str_replace(',','.',$val[5]);
            $val7=str_replace(',','.',$val[6]);
            if($cekada->num_rows()==0)
            {
                $data=array(
                    'id_kabupaten'=>1,
                    'id_tanaman'=>$val[2],
                    'id_kecamatan'=>$id_kecamatan,
                    'luas_panen'=>$val5,
                    'produktivitas'=>$val6,
                    'produksi'=>$val7,
                    'waktu'=>$waktu
                );

                $this->db->insert($table,$data);
//                $this->db->query("INSERT INTO $table VALUES(null, $val[2], $val[3], '$val[5]', $val5, $val6, $val7, '$waktu');");
            }
            else
            {
                $query=$this->db->query("UPDATE bahan_makanan SET luas_panen='$val5',produktivitas=$val6,produksi='$val7' WHERE id_tanaman='$val[2]' and waktu='$waktu';");
            }
        }
    }

    /*Insert dari data excel yang dicentang ke database*/
    public function insertKonsumsi($id_tanaman, $arr_data, $arr_check, $waktu){
        $cek = array_filter($arr_check);
        $table = 'ketersediaan';
        if (empty($cek)) {
            return;
        }
        $query = array();
        foreach ($arr_check as $index)
        {
            $index+=1;
            $val= $arr_data[$index];
            $cekada=$this->db->query("SELECT * from $table WHERE id_kabupaten = 1 and id_kecamatan='$val[1]' and id_tanaman=$id_tanaman and waktu='$waktu'");
            foreach ($val as &$row) {
               $row = str_replace(',','',$row);        
            }
           
            $str_f = strpos($val[11],'(');
            if($str_f !== false ){
                $val[11] = str_replace('(','-',$val[11]);
                $val[11] = str_replace(')','',$val[11]);
            }

            $str_f2 = strpos($val[13],'(');
            if($str_f2 !== false ){
                $val[13] = str_replace('(','-',$val[13]);
                $val[13] = str_replace(')','',$val[13]);
            }
            
            if($cekada->num_rows()==0){
                $data_insert = array(
                    'id_kabupaten' => 1,
                    'id_kecamatan' => $val[1],
                    'id_tanaman' => $id_tanaman,
                    'jumlah_penduduk' => $val[3], 
                    'luas_panen' => $val[4],
                    'provitas' => $val[5],
                    'produksi_padi' => $val[6],
                    'konversi_beras' => $val[7],
                    'bibit' => $val[8],
                    'pakan' => $val[9],
                    'tercecer' => $val[10],
                    'ketersediaan_beras' => $val[11],
                    'kebutuhan_konsumsi_riil' => $val[12],
                    'perimbangan' => $val[13],
                    'rasio_ketersediaan'=> $val[14],
                    'waktu' => $waktu
                );
                $query[] = $this->db->insert($table,$data_insert);
            }
            else {
                $update_arr = array(
                    'jumlah_penduduk' => $val[3], 
                    'luas_panen' => $val[4],
                    'provitas' => $val[5],
                    'produksi_padi' => $val[6],
                    'konversi_beras' => $val[7],
                    'bibit' => $val[8],
                    'pakan' => $val[9],
                    'tercecer' => $val[10],
                    'ketersediaan_beras' => $val[11],
                    'kebutuhan_konsumsi_riil' => $val[12],
                    'perimbangan' => $val[13],
                    'rasio_ketersediaan'=> $val[14],
                    'waktu' => $waktu,
                    'id_tanaman' => $id_tanaman
                );

                $where = array(
                    'id_tanaman' => $id_tanaman,
                    'waktu' => $waktu,
                    'id_kecamatan' => $val[1]
                );

                $this->db->set($update_arr);
                $this->db->where($where);
                $query[] = $this->db->update($table);
            }
        }

        foreach ($query as $row) {
            if($row === false){
                return false;
            }
        }
        return true;
    }

    public function getData($table)
    {
        $query=$this->db->query("SELECT kb.nama_kabupaten, kc.nama_kecamatan, p.id_bahan_makanan, j.nama_tanaman , p.luas_panen, p.produktivitas, p.produksi, MONTHNAME(p.waktu) as bulan, YEAR(p.waktu) as tahun  
        FROM kecamatan kc, kabupaten kb, $table p, jenis_tanaman j
        WHERE p.id_kecamatan=kc.id_kecamatan and p.id_kabupaten=kb.id_kabupaten and j.id_tanaman = p.id_tanaman;");
        return $query;
    }

    public function getDataById($table,$id)
    {
        $query=$this->db->query("SELECT id_bahan_makanan, jenis_tanaman, luas_panen, produktivitas, produksi, MONTHNAME(waktu) as bulan, YEAR(waktu) as tahun from $table WHERE id_bahan_makanan=$id;");
        return $query;
    }

    public function updateData($table,$id)
    {
        $jenis_tanaman=$this->input->post('jenis_tanaman');
        $luas_panen=$this->input->post('luas_panen');
        $produktivitas=$this->input->post('produktivitas');
        $produksi=$this->input->post('produksi');
        $waktu=date("Y-m-d",strtotime($this->input->post('waktu')));

        $query=$this->db->query("UPDATE $table SET jenis_tanaman='$jenis_tanaman', luas_panen=$luas_panen, produktivitas=$produktivitas, produksi=$produksi, waktu='$waktu' WHERE id_bahan_makanan=$id");
    }
}