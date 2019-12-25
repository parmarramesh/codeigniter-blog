<?php
class Category extends MY_Controller
{
    public function __construct(){
      parent::__construct();
    }

    public function index()
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['title']= "Latest Category";
            $data['category'] = $this->category_model->get_categories($this->page_data['langId']);

            $this->load->view('templates/header');
            $this->load->view('category/list-category', $data);
            $this->load->view('templates/footer');
        }
    }

    public function create()
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['languages'] = $this->language_model->get_languages();

            foreach ($data['languages'] as $language) {
                $this->form_validation->set_rules('categoryName_'.$language->id, $language->name.' Category name', 'required|callback_check_category_exists['.$language->id.']');
            }

            $data['title'] = "Create Category";


            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header');
                $this->load->view('category/create', $data);
                $this->load->view('templates/footer');
            } else {
                $last_id = $this->category_model->add_category();
                foreach ($data['languages'] as $key => $lang) {
                    $categoryNameArray = array(
                          'cid' => $last_id,
                          'lang_id' => $lang->id,
                          'category_name' => $this->input->post('categoryName_'.$lang->id),
                  );
                    $this->category_model->add_category_detail($categoryNameArray);
                }
                $this->session->set_flashdata('category_created', 'Category added successfully');
                redirect('category/index');
            }
        }
    }

    public function delete($id)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $this->category_model->delete_category($id);
            $this->category_model->delete_category_detail($id);
            redirect('category');
        }
    }

    public function posts($id)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $categoryTitle = $this->category_model->get_category_name($id, $this->page_data['langId']);
            $data['title'] = $categoryTitle;
            $data['posts'] = $this->category_model->get_post_by_category($id, $this->page_data['langId']);
            $this->load->view('templates/header');
            $this->load->view('posts/index', $data);
            $this->load->view('templates/footer');
        }
    }

    public function check_category_exists($category, $lang_id)
    {
        $this->form_validation->set_message('check_category_exists', 'Category already exist. Please choose a different one');
        if ($this->category_model->check_category_exists($category, $lang_id)) {
            return true;
        } else {
            return false;
        }
    }
}
