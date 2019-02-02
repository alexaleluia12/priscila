<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    
    /**
     * Cria novo usuario
     * @param array $data
     * @return bool true/false se for inserido corretamente
     */
    public function create($data)
    {
        $table_name = "user";
        return $this->db->insert($table_name, $data);
    }
    
    
    /**
     * Busca usuario pelo email
     * @param string $email
     * @return array
     */
    public function get($email)
    {
        $query = $this->db->get_where('user', array('email' => $email));
        return $query->row_array();
    }
    
    
    /**
     * Conta quantos usuarios tem esse email
     * @param string $email
     * @return int
     */
    public function countEmail($email)
    {
        $this->db->where('email', $email);
        $this->db->from('user');
        $resp = $this->db->count_all_results();
        return $resp;
    }
    
    /**
     * Busca usuario por id
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        $query = $this->db->get_where('user', array('id' => $id));
        return $query->row_array();  
    }
    
    /**
     * 
     * @param int $id
     * @param array $data
     * @return boll
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
    }
    
    /**
     * Array com erro de banco de dados
     * @return array
     */
    public function get_error()
    {
        return $this->db->error();
    }
    
    ///*****************
    
    /**
     * Cria array para ser inserido no banco de dados
     * @param string $name_full
     * @param string $email
     * @param string $password
     * @return array
     */
    public function pre_insert($name_full, $email, $password)
    {
        $nw_pass = password_hash($password, PASSWORD_DEFAULT);
        $name = explode(' ', $name_full)[0];
        $now = date("Y-m-d H:i:s");
     
        $data = array(
            'name' => $name,
            'name_full' => $name_full,
            'password' => $nw_pass,
            'date_insert' => $now,
            'email' => $email
        );
        
        return $data;
    }
    
    /**
     * 
     * @param type $email
     * @param type $password
     * @return mixed user array ou null se o usuario nao pode ser autenticado
     */
    public function authenticate($email, $password)
    {
        $user = $this->get($email);
        $out = null;
        if(count($user))
        {
            if(password_verify($password, $user['password']))
            {
                $out = $user;
            }
        }
        
        return $out;
    }
    
    /**
     * $sinal_id for null vai remover todos os sinais desse usuario, senao remove
     * apenas o sinal passado
     * @param int $user_id id do usuario logado
     * @param mixed $sinal_id id do sinal ou null
     */
    public function removesignal($user_id, $sinal_id, $to_remove)
    {
        $this->load->model('Stock_model', 'stock');
        if(is_null($sinal_id))
        {
            $lst_sinais_id = $this->stock->buysell($user_id);
            $i = 0;
            $sql = "INSERT INTO listusernao (`listgeral_id`, `user_id`) VALUES ";
            foreach ($lst_sinais_id as $value)
            {
                $id_sinal = $value['id'];
                $tipo = $value['type'];
                
                if($tipo == $to_remove)
                {
                    $sql .= "($id_sinal, $user_id),";
                    $i = 1;
                }
            }
            
            if($i == 1)
            {
                $nvsql = substr($sql, 0, strlen($sql) - 1);
                $this->db->query($nvsql);
            }
            
        } else    
        { 
            $this->db->insert('listusernao', ['user_id' => $user_id, 'listgeral_id' => $sinal_id]);
        }
    }
    
}