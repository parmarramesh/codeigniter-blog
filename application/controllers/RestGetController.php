<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * Description of RestGet
 *
 * @author https://roytuts.com
 */
class RestGetController extends CI_Controller
{
    use REST_Controller {
        REST_Controller::__construct as private __resTraitConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->library('session');
        $this->load->model('ContactModel', 'cm');
    }

    public function contacts_get()
    {
        $contacts = $this->cm->get_contact_list();

        if ($contacts) {
            $this->response($contacts);
        } else {
            $this->response(null, 404);
        }
    }

    public function contact_get()
    {
        if (!$this->get('id')) {
            $this->response('Invalid data, Please try again!', 400);
        }
        $contact = $this->cm->get_contact($this->get('id'));

        if ($contact) {
            $this->response($contact, 200); // 200 being the HTTP response code
        } else {
            $this->response('Data not found', 404);
        }
    }

    public function add_contact_post()
    {
        $contact_name = $this->post('contact_name');
        $contact_address = $this->post('contact_address');
        $contact_phone = $this->post('contact_phone');

        $result = $this->cm->add_contact($contact_name, $contact_address, $contact_phone);

        if ($result === false) {
            $this->response(array('status' => 'failed'));
        } else {
            $this->response(array('status' => 'success'));
        }
    }

    public function update_contact_put()
    {
        $contact_id = $this->put('contact_id');
        $contact_name = $this->put('contact_name');
        $contact_address = $this->put('contact_address');
        $contact_phone = $this->put('contact_phone');
        $result = $this->cm->update_contact($contact_id, $contact_name, $contact_address, $contact_phone);

        if ($result === false) {
            $this->response(array('status' => 'failed'));
        } else {
            $this->response(array('status' => 'success'));
        }
    }

    public function delete_contact_delete($contact_id)
    {
        $result = $this->cm->delete_contact($contact_id);

        if ($result === false) {
            $this->response(array('status' => 'failed'));
        } else {
            $this->response(array('status' => 'success'));
        }
    }

    // web api

    public function userLogin_post()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'failed', 'message' => 'Please Enter required field'));
        } else {
            $username = $this->input->post('username');
            $enc_pass = md5($this->input->post('password'));
            $userId = $this->auth_model->login($username, $enc_pass);

            if ($userId) {
                $userData = array(
                        "userId"=> $userId,
                        "username" => $username,
                        "isLogin" => true,
                        "language" => $this->config->item('language'),
                    );
                $this->session->set_userdata($userData);
                $this->response(array('status' => 'success', 'message' => 'Login successfully'));
            } else {
                $this->response(array('status' => 'failed', 'message' => 'Invalid username or password, Please try again!'));
            }
        }
    }

    public function blogs_get($offset = 0)
    {
        if (!islogin()) {
            $this->response(array('status' => 'failed' ,'message' => 'You have not login, Please login first'));
        } else {
            $config['base_url'] = base_url() . 'posts/index/';
            $config['total_rows'] = $this->db->where('user_id', $this->session->userdata('userId'))->from('posts')->count_all_results();
            $config['per_page'] = 3;
            $config['uri_sagment'] = 3;
            $config['attributes'] = array('class' => 'pagination');

            $this->pagination->initialize($config);

            $data['title']= "Latest Post";
            $data['posts'] = $this->post_model->get_posts(false, $config['per_page'], $offset);
            $this->response(array('data' => $data));
        }
    }

    public function add_blog_post()
    {
        if (!islogin()) {
            $this->response(array('status' => 'failed' ,'message' => 'You have not login, Please login first'));
        } else {
            // $data['title'] = "Create Post";
            // $data['categories'] = $this->post_model->get_categories($this->page_data['langId']);
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('body', 'Body', 'required');
            $this->form_validation->set_rules('category', 'Category', 'required');

            if ($this->form_validation->run() == false) {
                $this->response(array('status' => 'failed', 'message' => 'Please enter required fied'));
            } else {
                $config['upload_path'] = './assets/images/blogs';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = '2048';
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $new_name = time();
                $config['file_name'] = $new_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('userfile')) {
                    $error = array('error' => $this->upload->display_errors());
                    $post_image = 'noimage.png';
                } else {
                    $extension = pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION);
                    $post_image = $new_name.'.'.$extension;
                }

                $this->post_model->create_post($post_image);
                $this->response(array('status' => 'success', 'message' => 'Blog created successfully'));
            }
        }
    }

    public function edit_blog_get($slug){
      if(!islogin()){
        $this->response(array('status' => 'failed' ,'message' => 'You have not login, Please login first'));
      } else{
        $data['post'] = $this->post_model->get_posts($slug);

        $data['categories'] = $this->post_model->get_categories($this->page_data['langId']);
        if(empty($data['post'])){
          show_404();
        }
        $data['title'] = 'Update Blogs';

      $this->response(array('status' => 'success', 'data' => $data));
      }
    }

    public function update_blog_post(){
      if(!islogin()){
        $this->response(array('status' => 'failed' ,'message' => 'You have not login, Please login first'));
      } else{

        if(isset($_FILES['userfile']) && is_uploaded_file($_FILES['userfile']['tmp_name'])){
          $config['upload_path'] = './assets/images/blogs';
          $config['allowed_types'] = 'gif|jpg|jpeg|png';
          $config['max_size'] = '2048';
          $config['max_width'] = '2000';
          $config['max_height'] = '2000';
          $new_name = time();
          $config['file_name'] = $new_name;

          $this->load->library('upload', $config);

          if(!$this->upload->do_upload('userfile')){
            $error = array('error' => $this->upload->display_errors());
            $post_image = 'noimage.png';
          } else{
            $extension = pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION);
            $post_image = $new_name.'.'.$extension;
          }
        } else{
          $post_image = '';
        }
        $update = $this->post_model->update_blog($post_image);
        if($update){
            $this->response(array('status' => 'success', 'message' => 'Blog updated successfully'));
        } else{
          $this->response(array('status' => 'failed', 'message' => 'Error occurred during updating blog, Please try again!'));
        }
      }
    }

    public function delete_blog_delete($id){
      if(!islogin()){
        $this->response(array('status' => 'failed' ,'message' => 'You have not login, Please login first'));
      } else{
        $this->post_model->delete_post_comment($id);
        $this->post_model->delete_post($id);
        $this->response(array('status' => 'success', 'message' => 'Blog deleted successfully'));
      }
    }

    public function logout_get()
    {
        $this->session->unset_userdata('userId');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('isLogin');
        $this->session->unset_userdata('language');
        $this->response(array('status' => 'success' ,'message' => 'Logout successfully'));
    }
}
