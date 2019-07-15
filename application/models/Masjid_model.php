<?php

class Masjid_model extends CI_Model {

    public function get($id = NULL)
    {
        if ($id === NULL) {
            return $this->db->get('masjid')->result_array();
        }
        else {
            return $this->db->get_where('masjid', array('id' => $id))->result_array();
        }   
    }

    public function update($data, $id)
    {
        return $this->db->update('masjid', $data, array('id' => $id));
    }

    public function search($query) {
        $this->db->select('*');
        $this->db->from('masjid');
        $this->db->like('nama', $query);
        $this->db->or_like('alamat', $query);
        return $this->db->get()->result_array();
    }
}