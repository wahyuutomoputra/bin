<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Konten extends CI_Model {

    public function insert()
    {
        $this->load->library('upload');

        $foto                           = $_FILES['foto']['name'];
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $foto;

        $this->upload->initialize($config);
        if(!$this->upload->do_upload('foto')) {
            $error = array('error' => $this->upload->display_errors());
            return ['error' => true, 'errorMessage' => $error];
        }

        $data = array(
            'heading' => $this->input->post('heading'),
            'sub_heading' => $this->input->post('sub_heading'),
            'informasi' => $this->input->post('informasi'),
            'caption' => $this->input->post('caption'),
            'createdAt' => date('Y-m-d'),
            'foto' => $foto,
            'kategori' => $this->input->post('kategori')
        );

        $this->db->insert('Konten', $data);
        $insert_id = $this->db->insert_id();

        $tag = json_decode($_POST['Alltag'], true);
        foreach ($tag['listTag'] as $value) {
            $this->db->insert('Tag', array('idKonten' => $insert_id, 'name' => $value['name']));
        }

        $tag = json_decode($_POST['country'], true);
        foreach ($tag['data'] as $value) {
            $this->db->insert('konten_privileges', array('konten_id' => $insert_id, 'country_iso' => $value['name']));
        }

        return $data;
    }

    public function update()
    {
        $this->load->library('upload');

        $foto                           = $_FILES['foto']['name'];
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $foto;

        $this->upload->initialize($config);
        if(!$this->upload->do_upload('foto')) {
            $error = array('error' => $this->upload->display_errors());
            return ['error' => true, 'errorMessage' => $error];
        }

        $data = array(
            'heading' => $this->input->post('heading'),
            'sub_heading' => $this->input->post('sub_heading'),
            'informasi' => $this->input->post('informasi'),
            'caption' => $this->input->post('caption'),
            'createdAt' => date('Y-m-d'),
            'foto' => $foto,
            'kategori' => $this->input->post('kategori')
        );

        $this->db->where('id', $this->input->post('id'));
        return $this->db->update('Konten', $data);
    }

    public function get()
    {
        return $this->db->get('Konten');
    }

    public function getWithTag()
    {
        $query = $this->db->query("
                SELECT
                    k.*,
                    CAST( CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'tagId', t.id, 'name', t.NAME ) ), ']' ) AS JSON ) AS tags 
                FROM
                    Konten k
                    LEFT JOIN Tag t ON k.id = t.idKonten 
                GROUP BY
                    k.id
        ");

        return $query;
    }

    public function getByCountry()
    {
        $country = $this->input->get("country");
        $query = $this->db->query("
                SELECT
                    k.* 
                FROM
                    Konten k
                    JOIN konten_privileges kp ON ( k.id = kp.konten_id ) 
                WHERE
                    kp.country_iso = '".$country."'
        ");

        return $query;
    }

    public function getBy()
    {
        return $this->db->get_where('Konten', array('id' => $this->input->get('id')));
    }
}