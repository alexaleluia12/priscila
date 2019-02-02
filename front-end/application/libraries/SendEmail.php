<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use PHPMailer\PHPMailer\PHPMailer;

class SendEmail
{
    private $email;
    
    public function __construct()
    {
        $this->email = new PHPMailer;
        
        // **************
        // configuracao generica para envio de todos emails
        // **************
        $this->email->isSMTP();
        $this->email->setLanguage('pt');
        $this->email->Host = 'smtp.kinghost.net';
        $this->email->SMTPAuth = TRUE;
        $this->email->Username = 'lekapp@filmecu.com';
        $this->email->Password = 'orelhacansadamescubert&23';
        $this->email->SMTPAuth = TRUE;
        $this->email->CharSet = 'UTF-8';
        $this->email->SMTPSecure = 'tls';
        $this->email->Port = 587;
        // carbono copy para email da gerencia
        $this->email->isHTML(TRUE);
    }
    
    /**
     * 
     * @param string $email_origem
     * @param string $email_destino
     * @param string $assunto
     * @param string $corpo
     * @return string string tamanho zero caso o email foi envido corretamente ou uma string em 
     *  caso de falha, informado o erro
     */
    public function send($email_origem, $email_destino, $assunto, $corpo, $arquivo=null)
    {
        
        $from_name = 'Priscila';
        $this->email->setFrom($email_origem, $from_name);

        $this->email->addAddress($email_destino);
        
        
        $this->email->Subject = $assunto;
        $this->email->Body = $corpo;
        if(!is_null($arquivo))
        {
            $this->email->addAttachment($arquivo, "relatorio.pdf");
        }

        if (!$this->email->send())
        {
            return $this->email->ErrorInfo;
        }
        return "";
    }
}