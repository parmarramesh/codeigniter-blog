<?php
class Language extends MY_Controller
{
    public function changeLanguage($language)
    {
        $this->session->userdata['language'] = $language;
        $this->config->set_item('language', $language);
        $output = array('code' => 1, 'message'=> '');
        echo json_encode($output);
    }
}
