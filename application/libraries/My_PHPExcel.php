<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require  __DIR__.'/PHPExcel/IOFactory.php';

class My_PHPExcel extends PHPExcel_IOFactory
{
    // Transfer private __construct() in PHPExcel_IOFactory to public function
    public function __construct()
    {
    }
}
?>