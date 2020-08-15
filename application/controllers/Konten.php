<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konten extends API_Controller {

    public function __construct()
	{
		parent::__construct();
        //$this->auth();
        $this->load->model('M_Konten');
    }

    public function create_post()
    {
        if(
            isset($_POST['heading']) &&
            isset($_POST['sub_heading']) &&
            isset($_POST['informasi']) &&
            isset($_POST['caption']) &&
            isset($_POST['kategori']) 
        ) {
            $data = $this->M_Konten->insert();
            $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
        } else {
            $this->response(['status' => parent::HTTP_NOT_FOUND, 'message' => 'data tidak lengkap']);
        }
    }

    public function getAll_get()
    {
        $data = $this->M_Konten->get()->result();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

    public function getKontenBy_get()
    {
        $data = $this->M_Konten->getBy()->result();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);

    }

    public function update_post()
    {
        if(
            isset($_POST['heading']) &&
            isset($_POST['sub_heading']) &&
            isset($_POST['informasi']) &&
            isset($_POST['caption']) &&
            isset($_POST['kategori']) &&
            isset($_POST['id'])
        ) {
            $data = $this->M_Konten->update();
            $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
        } else {
            $this->response(['status' => parent::HTTP_NOT_FOUND, 'message' => 'data tidak lengkap']);
        }
    }

}

?>