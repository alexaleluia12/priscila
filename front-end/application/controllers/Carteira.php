<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Carteira extends CI_Controller
{
    const acaoCompra = 'C';
    const acaoVenda = 'V';
    
    public function operacao()
    {
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Stock_model');
        $this->load->model('Carteira_model');
        
        $acao = $this->input->get('acao');
        $code = $this->input->get('code');

        
        $this->form_validation->set_rules('valor_codigo', 'Código', 'trim|required');
        $this->form_validation->set_rules('operacao', 'Operação', 'trim|required');
        $this->form_validation->set_rules('preco', 'Preço', 'trim|callback_valorcerto');
        $this->form_validation->set_rules('qnt', 'Quantidade', 'trim|required|integer|greater_than[0]');
        
        $lst_codigos = $this->Stock_model->get();
        $data['title'] = 'Priscila';
        $data['lst_codigos'] = $lst_codigos;
        $data['acao'] = $acao;
        $data['runerros'] = array();
        
        if($this->form_validation->run() === false)
        {
            if(isset($code) and $this->input->server('REQUEST_METHOD') == 'GET')
            {
                $iduser = getuserid();
                $registro = $this->Carteira_model->getByOwner($iduser, $code);
                $_POST['valor_codigo'] = $registro['owner'];
                if($acao == self::acaoVenda)
                {
                    $_POST['qnt'] = $registro['amount'];
                }
                
            }
            $this->load->view("templates/header", $data);
            $this->load->view("forms/carteira", $data);
            $this->load->view("templates/footer");
        } else 
        {
            $user_id = getuserid();
            $value_price = str_replace(',', '.', $this->input->post('preco'));
            $value_price = (float) $value_price;
            $dados = array(
                "user_id" => $user_id,
                "owner" => $this->input->post('valor_codigo'),
                "price_media" => $value_price,
                "amount" => $this->input->post('qnt')
            );
            $tipo_operacao = $this->input->post('operacao');
            $compra = $this->Carteira_model->getByOwner($dados["user_id"], $dados["owner"]);
            if(count($compra) > 0)
            {
                $qnt_antiga = (int) $compra['amount'];
                $qnt_form = (int) $dados['amount'];
                if($tipo_operacao == self::acaoCompra)
                {
                    $nv_qnt = $qnt_antiga + $qnt_form;
                    $nv_preco = ((float)$compra['price_media'] + $dados['price_media']) / 2;
                } elseif($tipo_operacao == self::acaoVenda)
                {
                    // o preco a pos a venda continua o msm
                    $nv_preco = $compra['price_media'];
                    $nv_qnt = $qnt_antiga - $qnt_form;
                    if($nv_qnt < 0)
                    {
                        // erro
                        $data['runerros'] = array('Quantidade ultrapassa a existente');
                        $this->load->view("templates/header", $data);
                        $this->load->view("forms/carteira", $data);
                        $this->load->view("templates/footer");
                        return;
                    }
                }
                $new_data = array('price_media' => $nv_preco, 'amount' => $nv_qnt);
                if($nv_qnt == 0) 
                {
                    $this->Carteira_model->delete($compra['id']);
                } else 
                {
                    $this->Carteira_model->update($compra['id'], $new_data);
                }
            } else 
            {
                $this->Carteira_model->insert($dados);
            }
            
            redirect(base_url() . 'Stock/all');
        }
    }
     
    public function valorcerto($valor)
    {
        $toperacao = $this->input->post('operacao');
        if($toperacao == self::acaoVenda)
        {
            $valor = "1.0";
        }
        $novo = str_replace(',', '.', $valor);
        $notZero = ((float)$novo) > 0.0;
        if(!is_numeric($novo) or !$notZero)
        {
            $this->form_validation->set_message('valorcerto', '{field} incorreto');
            return false;
        }
        return true;
    }
}