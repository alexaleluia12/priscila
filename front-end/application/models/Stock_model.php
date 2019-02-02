<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * 
     * @param int $id
     * @param string $dateStart Y-m-d format
     * @param string $dateEnd Y-m-d format
     * @return CI_DB_result from valor table
     */
    public function get_serie($id, $dateStart, $dateEnd)
    {
        $this->db->select("date, close");
        $this->db->where("owner", $id);
        $this->db->where("date >=", $dateStart);
        $this->db->where("date <= ", $dateEnd);
        
        
        return $this->db->get("valor");
    }
    
    /**
     * Get all validos empresas(id, cod) or just one if is pased id
     * @param int $id
     * @return CI_DB_result array of values 
     */
    public function get($id=NULL)
    {
        $query = NULL;
        if($id === NULL)
        {
            $query = $this->db->get("validos");
        } else 
        {
            $query = $this->db->get_where("validos", array("id" => $id));
        }
        
        return $query->result_array();
    }
    
    /**
     * Gell all resent buy/sell signals from listing table, filter with no show from listusernao
     * 
     * @return CI_DB_result array of values 
     */
    public function buysell($id_user)
    {
        $this->db->select('listgeral.id, listgeral.owner, listgeral.type, listgeral.strength');
        $this->db->from('listgeral');
        $this->db->join('listusernao', 'listgeral.id = listusernao.listgeral_id', 'left');
        $this->db->where('listusernao.listgeral_id is null', null, false);
        $this->db->where('(listusernao.user_id is null OR listusernao.user_id = ' . $id_user . ')', null, false);
        
        $query = $this->db->get();

        return $query->result_array();
    }
    
    public function rsiConf($id)
    {
        $query = $this->db->get_where("rsiconf", array("owner" => $id));
        return $query->result_array();
    }
    
    public function getByCode($code)
    {
        $this->db->like('cod', $code);
        $query = $this->db->get('validos');
        
        return $query->result_array();
    }
    
  
}

