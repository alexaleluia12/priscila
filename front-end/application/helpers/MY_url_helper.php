<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * @return int id do usuario logado ou redireciona pala area de login se nao achou
 */
function getuserid()
{
    $CI = get_instance();
    $CI->load->library('session');
    
    $user_id = $CI->session->userdata('user_id');
    if($user_id)
    {
        return $user_id;
    }
    redirect(base_url() . 'User/login');
         
}

function logedin()
{
    $CI = get_instance();
    $CI->load->library('session');
    $user_id = $CI->session->userdata('user_id');
    if($user_id)
    {
        return true;
    }
    return false;
}

/**
 * echo runtime erros to out put, wapped into $top and $footer
 * @param array $lst
 * @param string $top
 * @param string $footer
 */
function runtimeErrors($lst, $top, $footer)
{
    foreach ($lst as $element)
    {
        echo $top . $element . $footer;
    }
}


/**
 * define a classe $class se $value == $check
 * @param string $value
 * @param string $check
 * @param string $class
 */
function classif($value, $check, $class)
{
    if($value == $check)
    {
        echo "class='".$class."'";
    }
}