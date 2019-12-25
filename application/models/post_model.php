<?php
class Post_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	public function get_posts($slug = false, $limit = false, $offset = false, $lang_id = 1) {
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		if ($slug == false) {
			$this->db->select('posts.title, posts.slug, posts.body, posts.image, posts.created_at, category_detail.category_name');
			$this->db->from('posts');
			$this->db->join('category', 'category.cid = posts.cid');
			$this->db->join('category_detail', 'category_detail.cid = category.cid');
			$this->db->order_by('posts.id', 'DESC');
			$this->db->where('posts.user_id', $this->session->userdata('userId'));
			$this->db->where('category_detail.lang_id', $lang_id);
			$query = $this->db->get();
			return $query->result_array();
		}
		$this->db->select('posts.id, posts.cid, posts.title, posts.slug, posts.body, posts.image, posts.created_at, category_detail.category_name');
		$this->db->from('posts');
		$this->db->join('category', 'category.cid = posts.cid');
		$this->db->join('category_detail', 'category_detail.cid = category.cid');
		$this->db->where('posts.slug', $slug);
		// $this->db->order_by('posts.id', 'DESC');
		$this->db->where('category_detail.lang_id', $lang_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function get_categories($lang_id) {
		$this->db->order_by('category_detail.category_name', 'ASC');
		$this->db->join('category_detail', 'category_detail.cid = category.cid');
		$this->db->where('user_id', $this->session->userdata('userId'));
		$this->db->where('category_detail.lang_id', $lang_id);
		$query = $this->db->get('category');
		return $query->result_array();
	}

	public function create_post($post_image) {
		$slug = url_title($this->input->post('title'));
		$data = array(
			'cid' => $this->input->post('category'),
			'title' => $this->input->post('title'),
			'slug' => $slug,
			'body' => $this->input->post('body'),
			'image' => $post_image,
			'user_id' => $this->session->userdata('userId'),
		);
		return $this->db->insert('posts', $data);
	}

	public function delete_post($id) {
		$image_name = $this->db->select('image')->from('posts')->where('id', $id)->get()->row()->image;
		$cwd = getcwd();
		$image_path = $cwd . "\\assets\\images\\blogs\\";
		chdir($image_path);
		if (file_exists($image_name) && $image_name != 'noimage.png') {
			unlink($image_name);
		}
		echo $image_path;
		echo $image_name;

		chdir($cwd);
		$this->db->where('id', $id);
		$this->db->delete('posts');
		return true;
	}

	public function update_blog($post_image) {
		$slug = url_title($this->input->post('title'), '-', true);
		$data = array(
			'cid' => $this->input->post('category'),
			'title' => $this->input->post('title'),
			'slug' => $slug,
			'body' => $this->input->post('body'),
		);
		if ($post_image != '') {
			$this->db->set('image', $post_image);
		}
		$this->db->where('id', $this->input->post('bid'));
		return $this->db->update('posts', $data);
	}

	public function create_comment($id) {
		$cdata = array(
			'post_id' => $id,
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email'),
			'comment' => $this->input->post('comment'),
		);
		$this->db->insert('comment', $cdata);
		$last_id = $this->db->insert_id();

		$query = $this->db->get_where('comment', array('id' => $last_id));
		return $query->row();
	}

	public function get_comment($id) {
		$this->db->order_by('created_at', 'desc');
		$this->db->where('post_id', $id);
		$query = $this->db->get('comment');
		return $query->result_array();
	}

	public function delete_post_comment($id) {
		$this->db->where('post_id', $id);
		return $this->db->delete('comment');
	}

	public function get_posts_for_report($slug = false, $limit = false, $offset = false, $lang_id = 1) {
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		if ($slug == false) {
			$this->db->select('posts.title, posts.slug, posts.body, posts.image, posts.created_at, category_detail.category_name');
			$this->db->from('posts');
			$this->db->join('category', 'category.cid = posts.cid');
			$this->db->join('category_detail', 'category_detail.cid = category.cid');
			$this->db->order_by('posts.id', 'DESC');
			$this->db->where('posts.user_id', $this->session->userdata('userId'));
			$this->db->where('category_detail.lang_id', $lang_id);
			$query = $this->db->get();
			return $query;
		}
		$this->db->select('posts.id, posts.cid, posts.title, posts.slug, posts.body, posts.image, posts.created_at, category_detail.category_name');
		$this->db->from('posts');
		$this->db->join('category', 'category.cid = posts.cid');
		$this->db->join('category_detail', 'category_detail.cid = category.cid');
		$this->db->where('posts.slug', $slug);
		// $this->db->order_by('posts.id', 'DESC');
		$this->db->where('category_detail.lang_id', $lang_id);
		$query = $this->db->get();
		return $query->row_array();
	}

}
?>
