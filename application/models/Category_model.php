<?php
class Category_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function add_category(){
		$catData = array(
			'user_id' => $this->session->userdata('userId')
		);
		$this->db->insert('category', $catData);
		return $this->db->insert_id();
	}

	public function add_category_detail($serializeCategory){
		return $this->db->insert('category_detail', $serializeCategory);
	}

	public function get_categories($lang){
		$this->db->order_by('category_detail.category_name', 'ASC');
		$this->db->join('category_detail', 'category_detail.cid = category.cid');
		$this->db->where('category.user_id', $this->session->userdata('userId'));
		$this->db->where('category_detail.lang_id', $lang);
		$query = $this->db->from('category');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function delete_category($id){
		$this->db->where('cid', $id);
		$this->db->delete('category');
		return true;
	}

	public function delete_category_detail($id){
		$this->db->where('cid', $id);
		$this->db->delete('category_detail');
		return true;
	}

	public function get_category_name($id, $lang_id){
		$this->db->where('cid', $id);
		$this->db->where('lang_id', $lang_id);
		return $this->db->select('category_name')->from('category_detail')->get()->row()->category_name;
	}

	public function get_post_by_category($id, $lang_id){
		$this->db->select('posts.title, posts.slug, posts.body, posts.image, posts.created_at, category_detail.category_name');
		$this->db->from('posts');
		$this->db->join('category', 'category.cid = posts.cid');
		$this->db->join('category_detail', 'category_detail.cid = category.cid');
		$this->db->order_by('posts.id', 'DESC');
		$this->db->where('posts.cid', $id);
		$this->db->where('category_detail.lang_id', $lang_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function check_category_exists($category, $lang_id){
		$this->db->join('category_detail', 'category_detail.id = category.cid');
		$query = $this->db->get_where('category', array('category_detail.category_name' => $category, 'category_detail.lang_id' => $lang_id, 'category.user_id' => $this->session->userdata('userId')));
		if(empty($query->row_array())){
			return true;
		} else{
			return false;
		}
	}
}
?>
