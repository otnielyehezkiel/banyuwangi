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

    public function getData($table)
    {
        $query=$this->db->query("SELECT kb.nama_kabupaten, kc.nama_kecamatan, p.id_bahan_makanan, p.jenis_tanaman, p.luas_panen, p.produktivitas, p.produksi, MONTHNAME(p.waktu) as bulan, YEAR(p.waktu) as tahun  
        FROM kecamatan kc, kabupaten kb, $table p
        WHERE p.id_kecamatan=kc.id_kecamatan and p.id_kabupaten=kb.id_kabupaten;");
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