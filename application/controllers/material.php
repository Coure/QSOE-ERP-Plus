<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Material_model');

    }
    public function list()
    {
        $this->load->helper('url');
        $this->load->view('header', array('title'=>'Material List'));
        $this->load->view('material_list');
        $this->load->view('footer');
    }
    public function detail()
    {
    }
    public function list_json()
    {
        echo json_encode($this->Material_model->get_array());
    }
}
?>