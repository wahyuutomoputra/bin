<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_User extends CI_Model {

    public function insert($data)
    {
        return $this->db->insert('User', $data);
    }

    public function login($email)
    {
        return $this->db->get_where('User', array('username' => $email));
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('User', $data);
    }

    public function get()
    {
        return $this->db->query("
        SELECT
            u.*,
            c.NAME AS countryName 
        FROM
            USER u
            JOIN country c 
        WHERE
            u.negara = c.iso
        ");
    }

    public function getBy()
    {
        return $this->db->get_where('User', array('id' => $this->input->get('id')));
    }

    public function getUsername($username)
    {
        return $this->db->get_where('User', array('username' => $username));
    }

    public function delete()
    {
        return $this->db->delete('User', array('id' => $this->input->get('id')));
    }

}

?>