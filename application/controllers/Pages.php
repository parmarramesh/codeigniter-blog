<?php
class Pages extends MY_Controller
{
    public function view($page = 'home')
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            if (!file_exists(APPPATH.'views/pages/'.$page.'.php')) {
                show_404();
            }
            $this->load->library('Custom');

            // $data['title']= ucfirst($page). ' ' .$this->custom->test();
            $data['title']= "Events Calender";
            $this->load->view('templates/header');
            $this->load->view('pages/'.$page, $data);
            $this->load->view('templates/footer');
        }
    }
}
