<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
#require_once APPPATH . '/libraries/datatable.php';

class excelmodel extends CI_MODEL
{
    public function getExcelData($filepath)
    {   
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($filepath);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        //extract to a PHP readable array format
        foreach ($cell_collection as $cell) {
            //$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $column=PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getCell($cell)->getColumn());
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            //header will/should be in row 1 only. of course this can be modified to suit your need.
            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }
        $data['header']=$header;
        $data['arr_data']=$arr_data;
        return $data;
    }
}