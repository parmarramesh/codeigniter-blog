<?php
  defined('BASEPATH') or exit('No direct script access allowed');
  class MY_Controller extends CI_Controller{
    public $page_data;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        if ($this->session->userdata('language')) {
            $language = $this->session->userdata('language');
        } else {
            $language = "english";
        }
        $this->page_data['langId'] = languageId($language);
        $this->page_data['langName'] = strtolower($language);
        $this->load->language('general_detail', $language);
				// $this->lang->load('general_detail', $language);
				// print_r($this->lang->is_loaded);exit;
    }
  }
 ?>
