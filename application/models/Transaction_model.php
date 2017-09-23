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
        $this->load->helper('array');
        $sheetData = csv_to_array($dataFile);

        $this->db->trans_strict(FALSE);
        $this->db->trans_start();

        $insertRows = 0;
        $updateRows = 0;

        // Remove records in the same date range to avoid duplication.
        $lastUpdates = array_column($sheetData, '创建日期');
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
            $this->trnxSrc = $data['事务来源'];
            $this->materialCode = $data['物料编码'];
            $this->trnxDate = strtotime($data['事务日期']);
            $this->trnxType = $data['事务类型'];
            $this->subinv = $data['仓库代码'];
            $this->locate = $data['货位'];
            $this->lot = $data['批次'];
            $this->owner = $data['拥有方'];
            $this->voucher = $data['单据号'];
            $this->source = $data['来源'];
            $this->qty = $data['主要数量'];
            $this->value = $data['主要金额'];
            $this->lastUpdate = strtotime($data['创建日期']);
            // var_dump($this);
            
            $this->db->insert($this->dbTable, $this);
            $insertRows += $this->db->affected_rows();
        }
        $this->db->trans_complete();

        return 'Inserted: ' . $insertRows;
    }
}
?>