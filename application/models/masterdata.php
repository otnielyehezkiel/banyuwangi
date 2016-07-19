<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class masterdata extends CI_MODEL
{
    public function getKabupaten()
    {
        $query=$this->db->query("SELECT * FROM kabupaten");
        if($query->num_rows() > 0)
        {
            return $query;
        }
        return NULL;
    }

    public function insertKabupaten($kode_kabupaten,$nama_kabupaten)
    {
        $query=$this->db->query("INSERT INTO kabupaten(kode_kabupaten,nama_kabupaten) VALUES('$kode_kabupaten','$nama_kabupaten')");
        return $query;
    }

    public function getKecamatan()
    {
        $query=$this->db->query("SELECT * FROM kecamatan");
        if($query->num_rows() > 0)
        {
            return $query;
        }
        return NULL;
    }

    public function insertKecamatan($id_kabupaten,$kode_kecamatan,$nama_kecamatan)
    {
        $query=$this->db->query("INSERT INTO kecamatan(id_kecamatan,kode_kecamatan,nama_kecamatan) VALUES('$id_kabupaten','$kode_kecamatan','$nama_kecamatan')");
        return $query;
    }

    public function getKecamatanFromIdKabupaten($id_kabupaten)
    {
        $query=$this->db->query("SELECT * FROM kecamatan WHERE id_kabupaten='$id_kabupaten'");
        if($query->num_rows() > 0)
        {
            return $query;
        }
        return NULL;
    }
}