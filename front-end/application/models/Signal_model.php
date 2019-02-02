<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Signal_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function signalByOwner($owner)
    {
        $result = $this->db->get_where('listgeral', 'owner = ' . $owner);
        
        return $result->row_array();
    }
}