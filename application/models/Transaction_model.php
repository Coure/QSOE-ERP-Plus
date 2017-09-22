<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model
{
    public $materialCode;
    public $trnxSrc;
    public $trnxDate;
    public $trnxType;
    public $subinv;
    public $locate;
    public $lot;
    public $owner;
    public $voucher;
    public $source;
    public $qty;
    public $value;
    public $lastUpdate;

    private $dbTable;

    public function __construct($tableName='ErpTransaction')
    {
        parent::__construct();
        $this->dbTable = $tableName;
        $this->load->database();
    }

    public function get_array()
    {
        $this->db
            ->select("*")
            ->from($this->dbTable)
            ->join('ErpMaterial', 'ErpMaterial.Code = ErpTransaction.MaterialCode', 'LEFT');
        return $this->db->get()->result_array();
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

        // Remove records in the same date range to avoid duplication.
        $lastUpdates = array_column($sheetData, 'AH');
        foreach ($lastUpdates as &$data)
        {
            $data = strtotime($data);
        }
        $minDate = min($lastUpdates);
        $maxDate = max($lastUpdates);
        unset($lastUpdates);
        $this->db
            ->where("LastUpdate >= ", $minDate)
            ->where("LastUpdate <= ", $maxDate)
            ->delete($this->dbTable);
        echo 'Deleted: ' . ($this->db->affected_rows());

        foreach ($sheetData as $data)
        {
            // Update if exist
            $this->trnxSrc = $data['A'];
            $this->materialCode = $data['D'];
            $this->trnxDate = strtotime($data['H']);
            $this->trnxType = $data['I'];
            $this->subinv = $data['J'];
            $this->locate = $data['K'];
            $this->lot = $data['L'];
            $this->owner = $data['M'];
            $this->voucher = $data['N'];
            $this->source = $data['O'];
            $this->qty = $data['Z'];
            $this->value = $data['Y'];
            $this->lastUpdate = strtotime($data['AH']);
            // var_dump($this);
            
            $this->db->insert($this->dbTable, $this);
            $insertRows += $this->db->affected_rows();
        }
        $this->db->trans_complete();

        return 'Inserted: ' . $insertRows;
    }
}
?>