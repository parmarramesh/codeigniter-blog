<?php
class Auth extends MY_Controller{
	public function register(){
		$this->load->view('templates/header');
		$this->load->view('auth/register');
		$this->load->view('templates/footer');
	}

	public function login(){
		if(islogin()){
			// $this->load->view('templates/header');
			// $this->load->view('pages/home');
			// $this->load->view('templates/footer');
			redirect('pages/view');
		} else{
			$this->load->view('templates/header');
			$this->load->view('auth/login');
			$this->load->view('templates/footer');
		}

	}

	public function userRegister(){
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_check_email_exists');
		$this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[32]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'matches[password]');

		if($this->form_validation->run() == FALSE){
			$this->load->view('templates/header');
			$this->load->view('auth/register');
			$this->load->view('templates/footer');
		} else{
			$enc_pass = md5($this->input->post('password'));
			$this->auth_model->register($enc_pass);
			$this->session->set_flashdata('user_registered', 'You are now registered and can log in');
			redirect('auth/login');
		}
	}

	public function userLogin(){
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run() == false){
			$this->load->view('templates/header');
			$this->load->view('auth/login');
			$this->load->view('templates/footer');
		} else{
			$username = $this->input->post('username');
			$enc_pass = md5($this->input->post('password'));
			$userId = $this->auth_model->login($username, $enc_pass);

			if($userId){
				$userData = array(
					"userId"=> $userId,
					"username" => $username,
					"isLogin" => true,
					"language" => $this->config->item('language'),
				);
				$this->session->set_userdata($userData);
				$this->session->set_flashdata('login_success', 'Login successfully');
				redirect('home');
			} else{
				$this->session->set_flashdata('login_failed', 'Invalid username or password, Please try again!');
				redirect('auth/login');
			}

		}
	}

	public function logout(){
		$this->session->unset_userdata('userId');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('isLogin');
		$this->session->unset_userdata('language');
		$this->session->set_flashdata('logout_success', 'Logout successfully');
		redirect('auth/login');

	}

	public function check_username_exists($username){
		$this->form_validation->set_message('check_username_exists', 'That username is taken. Please choose a different one');
		if($this->auth_model->check_username_exists($username)){
			return true;
		}else{
			return false;
		}
	}
	public function check_email_exists($email){
		$this->form_validation->set_message('check_email_exists', 'That email is taken. Please choose a different one');
		if($this->auth_model->check_email_exists($email)){
			return true;
		}else{
			return false;
		}
	}
}
?>
