<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_model extends CI_Model
{
    public $code;
    public $name;
    public $uom;
    public $lastUpdate;
    public $msq;
    private $dbTable;

    public function __construct($tableName='ErpMaterial')
    {
        parent::__construct();
        $this->dbTable = $tableName;
        $this->load->database();
    }

    public function get_array($fields = 'Code, Name, UOM, MSQ, LastUpdate')
    {
        $this->db->select($fields);
        return $this->db->get($this->dbTable)->result_array();
    }

    public function import($dataFile)
    {
        // Load PHPExcel
        $this->load->library('My_PHPExcel');

        // The 1st row of excel should be data.
        $excelObj = My_PHPExcel::load($dataFile);
        $sheetData = $excelObj->getSheet(0)->toArray(null, true, true, true);

        // Remove header.
        unset($sheetData[1]);

        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        $insertRows = 0;
        $updateRows = 0;

        
        foreach ($sheetData as $data)
        {
            // Update if exist
            $this->code = $data['A'];
            $this->name = $data['D'];
            $this->uom = $data['F'];
            $this->msq = $data['M'];
            $this->lastUpdate = strtotime($data['J']);
            
            $this->db->where('Code', $this->code);
            $this->db->update($this->dbTable, $this);
            if ($this->db->affected_rows() == 0)
            {
                // Insert if not exist
                $this->db->insert($this->dbTable, $this);
                $insertRows += $this->db->affected_rows();
            }
            else
            {
                $updateRows += $this->db->affected_rows();
            }
        }
        $this->db->trans_complete();

        return $insertRows + $updateRows;
    }
}
?>