<?php
  class Fullcalendar_model extends CI_model{
    public function fetch_all_event(){
      $this->db->order_by('id');
      return $this->db->get('events')->result_array();
    }

    public function insert_event($data){
      $this->db->insert('events', $data);
    }

    public function event_update($data, $id){
      $this->db->where('id', $id);
      $this->db->update('events', $data);
    }

    public function delete_event($id){
      $this->db->where('id', $id);
      $this->db->delete('events');
    }
  }
?>
