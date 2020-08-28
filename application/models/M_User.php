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
        //return $this->db->get_where('User', array('id' => $this->input->get('id')));
        return $this->db->query("
        SELECT
            u.*,
            c.NAME AS countryName 
        FROM
            USER u
            JOIN country c 
        WHERE
            u.negara = c.iso
            AND 
            u.id = ".$this->input->get('id')."
        ");
    }

    public function getUsername($username)
    {
        return $this->db->get_where('User', array('username' => $username));
    }

    public function delete()
    {
        return $this->db->delete('User', array('id' => $this->input->get('id')));
    }

    public function getTotal()
    {
        $user = $this->db->query("Select count(*) as user from User")->row_array();
        $berita = $this->db->query("select count(*) as berita from Konten")->row_array();
        $isu = $this->db->query("
            SELECT
                COUNT( * ) as isu
            FROM
                Konten 
            WHERE
                kategori IN ( 'Separatisme', 'Kejahatan Lintas Batas', 'Terorisme', 'PWNI' )
        ")->row_array();
        $bdi = $this->db->query("select count(*) as bdi from Konten where kategori = 'BDI'")->row_array();
        $bulan = $this->db->query("select count(*) as bulan from Konten where kategori = 'Laporan Bulanan'")->row_array();

        return [
            'total_user' => $user['user'],
            'total_berita' => $berita['berita'],
            'isu_bersama' => $isu['isu'],
            'bdi' => $bdi['bdi'],
            'laporan_bulanan' => $bulan['bulan']
        ];
    }

}

?>