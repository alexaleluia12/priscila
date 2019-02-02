<?php

/* 
 * Quando a quantide for zero, excluir da carteira
 * nao pode deixar inserir uma quantidade menor do que zero
 * 
 * Compra e venda de um sms ativo altera a quantidede e preco medio
 */

class Carteira_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Search for a register by user and id of stock
     * @param int $user_id
     * @param int $owner_id
     * @return array
     */
    public function getByOwner($user_id, $owner_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('owner', $owner_id);
        
        $resp = $this->db->get('carteira');
        return $resp->row_array();
    }
    
    public function getAll($user_id)
    {
        $this->db->where('user_id', $user_id);
        $resp = $this->db->get('carteira_completa');
        
        return $resp->result_array();        
    }
    
    /**
     * 
     * @param int $id_row
     * @param array $data price_media, amount as keys
     * @return bool
     */
    public function update($id_row, $data)
    {
        return $this->db->update('carteira', $data, 'id = ' . $id_row);
    }
    
    public function insert($data)
    {
        return $this->db->insert('carteira', $data);
    }
    
    public function delete($id_row)
    {
        return $this->db->delete('carteira', 'id = ' . $id_row);
    }
    
   
}