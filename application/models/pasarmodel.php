<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class pasarmodel extends CI_MODEL
{
	public function insertData($table, $data)
	{
		$query = $this->db->insert_batch($table, $data);
		return $query;
	}

	public function insertEach($table, $data)
	{

		foreach($data as $row){
			
		}
		return $query;
	}

	public function isInserted($tanggal,$id_pasar)
	{
		$select_arr = array('commodity_unit','c.commodity_id', 'commodity_name', 'commodity_title', 'price', 'price_yesterday');
		$this->db->select($select_arr);
		$this->db->from('hargakonsumen h');
		$this->db->join('commodity c', 'h.id_commodity = c.commodity_id');
		$this->db->join('pasar p', 'p.id = h.id_pasar');
		$this->db->where('h.tanggal',$tanggal);
		$this->db->where('h.id_pasar',$id_pasar);
		$query = $this->db->get();
		
		if($query->num_rows){
			return $query->result_array();
		}
		else return false;
	}

	public function getData($table)
	{
		$this->db->select('*');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function insertPasar($tanggal,$id_pasar,$data)
	{
		$query = array();
		foreach($data as $row){
			if(is_null($row['price'])){
				$row['price'] = 0;
			}
			if(is_null($row['price_yesterday'])){
				$row['price_yesterday'] = 0;
			}
			$ins = array(
				'id_commodity' => $row['commodity_id'],
				'price' => $row['price'],
				'price_yesterday' => $row['price_yesterday'],
				'id_pasar' => $id_pasar,
				'tanggal' => $tanggal
			);
			$query[] = $this->db->insert('hargakonsumen', $ins);
		}
		foreach ($query as $row) {
            if($row === false){
                return false;
            }
        }
        return true;
	}

	public function getGrafik($id_pasar)
	{
		$this->db->select("commodity_name, price");
		$this->db->select("DATE_FORMAT(tanggal, '%d/%m/%Y') AS date", FALSE);
		$this->db->from('hargakonsumen h');
		$this->db->join('commodity c', 'h.id_commodity = c.commodity_id');
		$this->db->where('id_pasar',$id_pasar);
		$this->db->where('tanggal >= DATE_SUB(NOW(), INTERVAL 30 day)');
		$this->db->order_by('id_commodity, tanggal');
		$query = $this->db->get();

		return json_encode($query->result_array());
	}

}

?>