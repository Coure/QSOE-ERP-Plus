<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaction_model');

    }
    public function list()
    {
        $this->load->helper('url');
        $this->load->view('header', array('title'=>'Transaction List'));
        $this->load->view('transaction_list');
        $this->load->view('footer');
    }
    public function detail()
    {
    }
    public function list_json()
    {
        echo json_encode($this->Transaction_model->get_array());
    }
    public function import()
    {
        echo $this->Transaction_model->import(VIEWPATH.'uploads/Singamas_INV_物料出入库明细报表.xlsx');
    }
}
?>