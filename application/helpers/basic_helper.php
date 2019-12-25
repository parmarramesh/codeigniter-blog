<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('test_method'))
{
    function islogin()
    {
    	$CI =& get_instance();
      $CI->load->library('session');
        if($CI->session->userdata('isLogin')){
        	return true;
        } else{
        	return false;
        }
    }
}

if(!function_exists('languageId')){
  function languageId($language = ''){
    $CI =& get_instance();
    $current_lang = $CI->language_model->get_language_id(strtolower($language));
    if(!empty($current_lang) && isset($current_lang->id)){
      return $current_lang->id;
    }
    else{
      return 0;
    }
  }
}
