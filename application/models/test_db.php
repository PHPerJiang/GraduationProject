<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: jiangyu01
 * Date: 2018/12/19
 * Time: 13:24
 * @property CI_DB db
 */

class Test_db extends CI_Model{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all(){
        $query = $this->db->get('graduation_project');
        return $query->row_array();
    }
}