<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Follow_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function create($user_id, $owner_id)
    {
        $this->db->insert('listacompanhar', ['owner' => $owner_id, 'user_id' => $user_id]);
    }
    
    public function remove($user_id, $owner_id)
    {
        $this->db->where('owner', $owner_id);
        $this->db->where('user_id', $user_id);
        
        $this->db->delete('listacompanhar');
    }
    
    public function removeall($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete('listacompanhar');
    }
    
    public function get($user_id)
    {
        $query = $this->db->get_where("listacompanhar", array("user_id" => $user_id));
        
        return $query->result_array();
    }
}