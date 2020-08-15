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

        return $this->db->insert('Konten', $data);
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

    public function getBy()
    {
        return $this->db->get_where('Konten', array('id' => $this->input->get('id')));
    }
}