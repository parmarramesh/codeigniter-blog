<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of ContactModel
 *
 * @author https://roytuts.com
 */
class ContactModel extends CI_Model {

	private $contact = 'contact';

	function get_contact_list() {
		$query = $this->db->get($this->contact);
		if ($query) {
			return $query->result();
		}
		return NULL;
	}

	function get_contact($id) {
		$query = $this->db->get_where($this->contact, array("contact_id" => $id));
		if ($query) {
			return $query->row();
		}
		return NULL;
	}

	function add_contact($contact_name, $contact_address, $contact_phone) {
        $data = array('contact_name' => $contact_name, 'contact_address' => $contact_address, 'contact_phone' => $contact_phone);
        $this->db->insert($this->contact, $data);
    }

		function update_contact($contact_id, $contact_name, $contact_address, $contact_phone) {
        $data = array('contact_name' => $contact_name, 'contact_address' => $contact_address, 'contact_phone' => $contact_phone);
        $this->db->where('contact_id', $contact_id);
        $this->db->update($this->contact, $data);
    }

		function delete_contact($contact_id) {
        $this->db->where('contact_id', $contact_id);
        $this->db->delete($this->contact);
    }

}
