<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_model extends CI_Model
{
    public $code;
    public $description;
    public $uom;
    public $updateDate;
    private $dbTable;

    public function __construct($tableName='ErpMaterial')
    {
        parent::__construct();
        $this->dbTable = $tableName;
        $this->load->database();
    }

    public function get_array()
    {
        $this->db->select('id, Code, Description, UOM, MinStock');
        return $this->db->get($this->dbTable)->result_array();
    }

    public function import($dataFile)
    {
        // Load PHPExcel
        $this->load->library('My_PHPExcel', PHPExcel);

        // The 1st row of excel should be data.
        $excelObj = PHPExcel::load(VIEWPATH.$dataFile);

        $sheetData = $excelObj->getSheet(0)->toArray(null, true, true, true);

        // Remove header.
        unset($sheetData[1]);

        // Select column A, D, F and transform to 2-dimentional array by array_map
        $selectData = array_map(null,
            array_column ($sheetData, 'A'),
            array_column ($sheetData, 'D'),
            array_column ($sheetData, 'F')
        );

        // Transfer 2-dimensional array to dictionary array
        $selectData = table_to_dict($selectData, array('Code', 'Description', 'UOM'));

        // Connect to database and start transaction
        // $this->load->database();
        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        $insertRows = 0;
        $updateRows = 0;

        foreach ($selectData as $data)
        {
            // Update if exist
            $this->db->where('Code', $data['Code']);
            $this->db->update($this->dbTable, $data);
            if ($this->db->affected_rows() == 0)
            {
                // Insert if not exist
                $this->db->insert($this->dbTable, $data);
                $insertRows += $this->db->affected_rows();
            }
            else
            {
                $updateRows += $this->db->affected_rows();
            }
        }
        $this->db->trans_complete();
        // echo 'Inserted: '.$insertRows.' Updated: '.$updateRows;

        return $insertRows + $updateRows;
    }
}
?>