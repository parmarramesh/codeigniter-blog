<?php
class Language_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function get_languages(){
		return $this->db->get('languages')->result();
	}

	public function get_language_id($language){
		return $this->db->get_where('languages', array('name' => $language))->row();
	}
}
?>
