<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of datatable
 *
 * @author mozar
 */
class datatable extends CI_Model {

    public function get_datatable($query, $columns = array(), $request) {
//        $q = $this->db->query($count_query)->result_array();
//        $jumlahData = 0;
//        if (count($q) > 0) {
//            $row = $q[0];
//            foreach ($row as $key => $val) {
//                $jumlahData = (int) $val;
//                break;
//            }
//        }
        $my_limit = "limit " . (int) $request['start'] . "," . (int) $request['length'];
        $my_filter = '';
        if (strlen($request['search']['value']) > 0) {
            if (is_array($columns) && count($columns) > 0) {
                $my_filter = 'where ';
                $sep = '';
                foreach ($columns as $column) {
                    $my_filter .= $sep . $column['name'] . " like '%" . $request['search']['value'] . "%' ";
                    $sep = ' OR ';
                }
            }
        }
        $my_order = '';
        if (isset($request['order']) && is_array($request['order'])) {
            $my_order = ' order by ';
            $sep = '';
            $n_column = count($columns);
            $order = $request['order'];
            for ($i = 0, $n = count($order); $i < $n; $i++) {
                $c_order = $order[$i];
                $column_number = intval($c_order['column']);
                if ($column_number < $n_column) {
                    $my_order.=$sep . $columns[$column_number]['name'] . ' ' . $c_order['dir'];
                    $sep = ', ';
                }
            }
        }
//        if ((int) $request['order'][0]['column'] < count($columns)) {
//            $my_order = ' order by ' . $columns[(int) $request['order'][0]['column']] . ' ' . $request['order'][0]['dir'];
//        }
        $count_query = "select count(*) from ($query) as myquery $my_filter";
        $q = $this->db->query($count_query)->result_array();
        $jumlahData = 0;
        if (count($q) > 0) {
            $row = $q[0];
            foreach ($row as $key => $val) {
                $jumlahData = (int) $val;
                break;
            }
        }
        $sql = "select * from ($query) as myquery $my_filter $my_order $my_limit";
        $q = $this->db->query($sql)->result_array();
        $hasil = array();
        foreach ($q as $row) {
            $baris = self::array_kosong($columns);
            foreach ($row as $cellName => $cell) {
                $index = self::index_dalam_array($cellName, $columns);
                if ($index >= 0){
                    $baris[$index] = $cell;
                    //$baris[]=$cell;
                }
            }
            $hasil[] = $baris;
        }
        return array('data' => $hasil, 'recordsFiltered' => $jumlahData,
            'recordsTotal' => $jumlahData, 'draw' => (int) $request['draw'],
            'query' => $sql
        );
    }

    private static function index_dalam_array($cellName, $columns) {
        $i = 0;
        foreach ($columns as $key => $col) {
            if ($col['name'] == $cellName) {
                return (int)$key;
            }
            $i++;
        }
        return -1;
    }

    private static function array_kosong($columns) {
        $hasil = array();
        for ($i = 0, $n = count($columns); $i < $n; $i++) {
            $hasil[$i] = '';
        }
        return $hasil;
    }

}
