<?php

class User extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }
    
    public function logout()
    {
        $this->load->library('session');
        $this->session->unset_userdata('user_id');
        
        redirect(base_url());
        
    }
    
    public function login()
    {
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->library('form_validation');  
        
        $this->form_validation->set_rules('username', 'Login', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Senha', 'trim|required');
        
        if($this->session->userdata('user_id'))
        {
            redirect(base_url() . 'Stock/all');
        }
        
        $data['title'] = 'Priscila';
        $data['runerros'] = array();
        if($this->form_validation->run() === false)
        {
            $this->load->view("templates/header", $data);
            $this->load->view("forms/login", $data);
            $this->load->view("templates/footer");
        } else
        {
            $email = $this->input->post('username');
            $password = $this->input->post('password');
            
            $response = $this->User_model->authenticate($email, $password);
            if(is_null($response))
            {
                $data['runerros'] = array('Usuário/Senha inválidos');
                $this->load->view("templates/header", $data);
                $this->load->view("forms/login", $data);
                $this->load->view("templates/footer");
            } else
            {
                $this->session->set_userdata('user_id', $response['id']);
                redirect(base_url() . 'Stock/all');
            }
            
        }
        
    }
    
    public function newuser()
    {
        // tirar senha secreta no futuro
        $this->load->helper('form');
        $this->load->library('form_validation');        

        $this->form_validation->set_rules('full_name', 'Nome Completo', 'trim|required|max_length[289]');
        $this->form_validation->set_rules('pass1', 'Senha', 'trim|required|max_length[255]|min_length[8]');
        $this->form_validation->set_rules('pass2', 'Confirmação Senha', 'trim|required|max_length[255]|min_length[8]|callback_passequal');
        $this->form_validation->set_rules('email_user', 'Email', 'trim|required|valid_email|is_unique[user.email]|max_length[289]');
        $this->form_validation->set_rules('secreta', 'Secreta', 'trim|required|callback_secreta');
        
        $data['title'] = 'Usuário';
        
        if($this->form_validation->run() === false)
        {
            $this->load->view("templates/header", $data);
            $this->load->view("forms/contauser");
            $this->load->view("templates/footer");
        } else 
        {
            // sucesso
            $nome = $this->input->post('full_name');
            $email = strtolower($this->input->post('email_user'));
            $password = $this->input->post('pass1');
            
            $to_insert = $this->User_model->pre_insert($nome, $email, $password);
            $response = $this->User_model->create($to_insert);
            if($response)
            {
                $data['msg'] = 'Usuário criado';
                $this->load->view("templates/header", $data);
                $this->load->view("pages/sucess", $data);
                $this->load->view("templates/footer");
            } else
            {
                $pq = $this->User_model->get_error();
                $this->log->write_log('debug', 'Falha inserir usuario = ' . 
                        implode('|', $pq)
                );
                
                $data['msg'] = 'falha ao criar Usuário.';
                $this->load->view("templates/header", $data);
                $this->load->view("pages/fail", $data);
                $this->load->view("templates/footer");
            }
            
            
        }
        
    }
    
    
    public function secreta($valor)
    {
        $chave = 'coruja';
        if($valor != $chave)
        {
            $this->form_validation->set_message('secreta', '{field} incorreto');
            return false;
        }
        return true;
    }
    
    public function passequal($v)
    {
        
        $v1 = $this->input->post('pass1');
        $v2 = $this->input->post('pass2');
        if($v1 != $v2)
        {
            $this->form_validation->set_message('passequal', 'senhas divergem');
            return false;
        }
        return true;
    }
    
    
    public function passrecover()
    {
        // tirar senha secreta no futuro
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('SendEmail', null, 'myemail');
        $this->load->library('Project');
        

        $this->form_validation->set_rules('email_user', 'Email', 'trim|required|valid_email|max_length[289]');
        
        $data['title'] = 'Priscila';
        $data['muser'] = array();
        $data['runerros'] = array();
        
        if($this->form_validation->run() === false)
        {
            $this->load->view("templates/header", $data);
            $this->load->view("forms/senha", $data);
            $this->load->view("templates/footer");
        } else 
        {
            $email = $this->input->post('email_user');            
            $to_insert = $this->User_model->get($email);
            
            if(is_array($to_insert))
            {
                $nvsenha = md5(rand(5, 15) . date('Y-m-d H:i:s'));
                $nvsenha = substr($nvsenha, 0, 8);
                $senhaemb = password_hash($nvsenha, PASSWORD_DEFAULT);

                $to_insert['password'] = $senhaemb;

                $this->User_model->update($to_insert['id'], $to_insert);
                $corpo_email = $to_insert['name'] . " sua nova senha:<br>$nvsenha";
                $assunto = "Recuperação de senha";
                
                $resp_email = $this->myemail->send(Project::email,
                        $to_insert['email'], $assunto, $corpo_email
                );
                if(strlen($resp_email) > 0)
                {
                    log_message('debug', 'Falha ao enviar email' . $resp_email);
                    $data['muser'] = array('type' => 'F', 'msg' => "Não conseguimos enviar email");
                } else
                {
                    $data['muser'] = array('type' => 'S', 'msg' => "Email enviado");
                }
            } else
            {
                $data['runerros'] = array("Email não encontrado");
            }
            
            $this->load->view("templates/header", $data);
            $this->load->view("forms/senha", $data);
            $this->load->view("templates/footer");
            
            
            
            /*
             * setar nova senha
             * salvar e mandar email para cara
             * usar phpMailer
             */
        }
    }
    
    /**
     * limpa todos os sinais mostrado do usuario, ela ja pode ter limpado antes
     */    
    public function clearbuy()
    {
        
       $id_user = getuserid();
       $type = 'B';
       $this->User_model->removesignal($id_user, null, $type);
       
       redirect(base_url() . 'Stock/all');
        
    }
    
    public function clearsell()
    {
        $id_user = getuserid();
        $type = 'S';
        $this->User_model->removesignal($id_user, null, $type);
        redirect(base_url() . 'Stock/all');
    }
    
    public function clearone($id)
    {
        $this->load->model('Signal_model');
        $id_user = getuserid();
        
        $id_sinal = $this->Signal_model->signalByOwner($id)['id'];
        $this->User_model->removesignal($id_user, $id_sinal, null);
        
        redirect(base_url() . 'Stock/all');
    }
    
    public function fadd($id)
    {
        $this->load->model('Follow_model');
        $id_user = getuserid();
        
        $this->Follow_model->create($id_user, $id);
        
        redirect(base_url() . 'Stock/all');
    }
    
    public function fremove($id)
    {
        $this->load->model('Follow_model');
        $id_user = getuserid();
        
        $this->Follow_model->remove($id_user, $id);
        
        redirect(base_url() . 'Stock/all');
    }
    
    public function fremovelall()
    {
        $this->load->model('Follow_model');
        $id_user = getuserid();
        
        $this->Follow_model->removeall($id_user);
        
        redirect(base_url() . 'Stock/all');
    }
    
    public function sugestao()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('SendEmail', null, 'myemail');
        $this->load->library('Project');
        

        $this->form_validation->set_rules('assunto', 'Assunto', 'trim|required');
        $this->form_validation->set_rules('texto', 'Texto', 'trim|required|max_length[300]|min_length[5]');
        
        $data['title'] = 'Melhorar';
        $data['muser'] = array();
        $data['runerros'] = array();
        
        if($this->form_validation->run() === false)
        {
            $this->load->view("templates/header", $data);
            $this->load->view("forms/sugestao", $data);
            $this->load->view("templates/footer");
        } else
        {
            
            $texto =  $this->input->post('texto');
            $assunto = $this->input->post('assunto');
            $para = "alexdiasaleluia@gmail.com";
            $usuario = $this->User_model->getById(getuserid());
            
            $corpo = $usuario['name_full'] . " (" . $usuario['email']. ") enviou<br>$assunto<br>$texto";
            
            $rmail = $this->myemail->send(Project::email, $para, "Sugestão", $corpo);
            
            
            if(strlen($rmail) > 0)
            {
                log_message('debug', 'Falha ao enviar email, sugestao. ' . $rmail);
                $data['muser'] = array('type' => 'F', 'msg' => "Não enviado");
            } else
            {
                $data['muser'] = array('type' => 'S', 'msg' => "Envidado com sucesso");
            }
            
            $this->load->view("templates/header", $data);
            $this->load->view("forms/sugestao", $data);
            $this->load->view("templates/footer");
        }
    }
    
    public function password()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $manage = "password";
        $this->form_validation->set_rules('passold', 'Senha Antigas', 'trim|required|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('pass1', 'Senha', 'trim|required|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('pass2', 'Confirmação Senha', 'trim|required|max_length[20]|callback_passequal');
        
        $data['title'] = "Trocar senha";
        $data['muser'] = array();
        $data['manager'] = $manage;
        $data['runerros'] = array();
        
        if($this->form_validation->run() === false)
        {
            $this->load->view("templates/header", $data);
            $this->load->view("forms/psenha", $data);
            $this->load->view("templates/footer");
        } else 
        {
            $nvsenha = $this->input->post('pass2');
            $shantiga = $this->input->post('passold');
            
            $id_user = getuserid();
            $user = $this->User_model->getById($id_user);            
            if(password_verify($shantiga, $user['password']))
            {
                $senhaemb = password_hash($nvsenha, PASSWORD_DEFAULT);
                $to_insert['password'] = $senhaemb;

                $resp = $this->User_model->update($id_user, $to_insert);
                if($resp)
                {
                    $data['muser'] = array('type' => 'S', 'msg' => "Senha alterada");
                } else
                {
                    log_message('debug', 'Falha mudar senha ' . implode(' |', $this->db->error()));
                    $data['muser'] = array('type' => 'F', 'msg' => "Não foi possível alterar a senha");
                }
            } else
            {
                $data['runerros'] = array("Senha antiga não confere. Informe a senha usada no login.");
            }
            
            $this->load->view("templates/header", $data);
            $this->load->view("forms/psenha", $data);
            $this->load->view("templates/footer");
        }
    }
    
    
    public function account()
    {
        // trazer preenchido
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('SendEmail', null, 'myemail');
        $this->load->library('Project');
        
        $manage = "account";

        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[250]');
        $this->form_validation->set_rules('name', 'Nome Completo', 'trim|min_length[2]|max_length[290]');
        
        $data['title'] = "Troca Dados Pessoais";
        $data['muser'] = array();
        $data['manager'] = $manage;
        $data['runerros'] = array();
        if($this->form_validation->run() === false)
        {
            if($this->input->server('REQUEST_METHOD') == 'GET')
            {
                $user_id = getuserid();
                $user = $this->User_model->getById($user_id);
                $_POST['email'] = $user['email'];
                $_POST['name'] = $user['name_full'];
            }

            $this->load->view("templates/header", $data);
            $this->load->view("forms/pconta", $data);
            $this->load->view("templates/footer");
        } else 
        {
            /*
             * Salva se dados forem diferentes
             * email ainda nao pode ser duplicado
             * 
             */

            $user_id = getuserid();
            $user = $this->User_model->getById($user_id);
            $oemail = $user['email'];
            $ofullname = $user['name_full'];
            
            $nemail = strtolower($this->input->post('email'));
            $nfullname = $this->input->post('name');
            
            $seguir = true;
            if($oemail != $nemail and $this->User_model->countEmail($nemail) > 0)
            {
                $data['runerros'] = array('Email já usado no sistema');
                $seguir = false;
            }
            
            if($seguir and $oemail != $nemail)
            {
                $corpo = "email alterado para: " . $nemail;
                $origem = Project::email;
                $assunto = "mudança email";
                $destino = $nemail;
                
                $resp = $this->myemail->send($origem, $destino, $assunto, $corpo);
                if(strlen($resp) > 0)
                {
                    $seguir = false;
                    $data['runerros'] = array('Email: ' . $nemail . ' inválido, informe outro por favor');
                }
            }
            
            if($seguir)
            {
                $novos_dados = array('name_full' => $nfullname, 'email' => $nemail);
                $resp = $this->User_model->update($user_id, $novos_dados);
                $msg = $resp ? 
                       array("type" => "S", "msg" => "Dados salvos com sucesso") : 
                       array("type" => "F", "msg" => "Não foi possível salvar os dados");
                $data["muser"] = $msg;
                
            }
            
            
            $this->load->view("templates/header", $data);
            $this->load->view("forms/pconta", $data);
            $this->load->view("templates/footer");
        }
    }
}
