<?php
/**
 * Created by PhpStorm.
 * User: Nobby Phala
 * Date: 19/06/2016
 * Time: 15:55
 */

class datamodel extends CI_Model
{
    public function loaddata($table,$id_kabupaten=0,$id_kecamatan=0, $tahun_data=0,$where=array(),$sum)
    {

        if($tahun_data==0)
        {
            $query=$this->db->query("select max(EXTRACT(YEAR from waktu)) as tahun_data from $table");
            if($query->num_rows() > 0)
            {
                $res=$query->row();
                $tahun_data=$res->tahun_data;
            }
        }
        $this->db->select('nama_kabupaten');
        $this->db->select('nama_kecamatan');
        $this->db->select("$table.*");
        $this->db->from($table);
        $this->db->where("EXTRACT(YEAR from waktu)=",$tahun_data);


        if(!empty($where))
        {
            foreach ($where as $key=>$val)
            {
                $this->db->where($key,$val);
            }
        }

        if($id_kecamatan!=0)
        {
            $this->db->join('kecamatan',"kecamatan.id_kecamatan=$table.id_kecamatan");
            $this->db->join('kabupaten',"kabupaten.id_kabupaten=$table.id_kabupaten");
            $this->db->where("$table.id_kecamatan",$id_kecamatan);
            $this->db->where("$table.id_kabupaten",$id_kabupaten);
            $query=$this->db->get();
        }
        else
        {
            if($id_kabupaten!=0)
            {
                
                $this->db->join('kecamatan',"kecamatan.id_kecamatan=$table.id_kecamatan");
                $this->db->join('kabupaten',"kabupaten.id_kabupaten=$table.id_kabupaten");
                //$this->db->where("$table.id_kecamatan",$id_kecamatan);
                $this->db->where("$table.id_kabupaten",$id_kabupaten);

//                if(!empty($sum))
//                {
//                    foreach ($sum as $val)
//                    {
//                        $this->db->select_sum($val);
//                    }
//                }
                $query=$this->db->get();
            }
            else
            {
                $this->db->join('kecamatan',"kecamatan.id_kecamatan=$table.id_kecamatan");
                $this->db->join('kabupaten',"kabupaten.id_kabupaten=$table.id_kabupaten");

                if(!empty($sum))
                {
                    foreach ($sum as $val)
                    {
                        $this->db->select_sum($val);
                    }
                }

                $this->db->group_by($table.".id_kabupaten");
                $query=$this->db->get();
            }
        }


        if($query->num_rows >0)
        {
            $res=$query->result_array();

            for ($i=0;$i<count($res);$i++)
            {
                $keys = array_keys($res[$i]);
                unset($res[$i][$keys[2]]);
                unset($res[$i][$keys[3]]);
                unset($res[$i][$keys[4]]);
                if($id_kabupaten==0)
                {
                    $res[$i][$keys[1]]="-";
                }
                $data["data"][$i]=array_values($res[$i]);

            }
            
            print json_encode($data);
        }
        else{
            $data["data"]=array();
            print json_encode($data);
        }
    }

    public function getJenisData($table,$field)
    {
        $this->db->select("$field as jenis_data");
        $this->db->group_by($field);
        $this->db->from($table);
        $this->db->where("$field IS NOT NULL", null);

        $query=$this->db->get();

        if($query->num_rows() >0)
        {
            return $query->result_array();
        }

        return NULL;
    }

    public function get_year_range_data($table,$id_kabupaten,$id_kecamatan,$where,$sum)
    {
        $where_str="EXTRACT(YEAR from waktu)<=YEAR(CURDATE()) and  EXTRACT(YEAR from waktu)>=YEAR(CURDATE())-5";
        $this->db->select("EXTRACT(YEAR from $table.waktu) as tahun_data");
        $this->db->from($table);
        $this->db->where($where_str);
        $this->db->group_by("tahun_data");

        if($id_kecamatan!=0)
        {
            $this->db->where("$table.id_kecamatan",$id_kecamatan);
        }

        if($id_kabupaten!=0)
        {
            $this->db->where("$table.id_kabupaten",$id_kabupaten);
        }


        if(!empty($where))
        {
            foreach ($where as $key=>$val)
            {
                $this->db->where($key,$val);
            }
        }

        if(!empty($sum))
        {
            foreach ($sum as $val)
            {
                $this->db->select_sum($val);
            }
        }

        $query=$this->db->get();

        if($query->num_rows >0)
        {
            $res=$query->result_array();
            for ($i=0; $i<count($res);$i++)
            {
                $data[$i]=array_values($res[$i]);
            }

            print json_encode($data);
        }
        else{
            $data=array();
            print json_encode($data);
        }
    }
}