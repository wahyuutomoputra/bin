<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeritaAcara extends API_Controller {
    public function __construct()
	{
		parent::__construct();
        //$this->auth();
        $this->load->library('form_validation');
        $this->load->model('M_BeritaAcara');
    }

    public function insertBA_post()
    {
        $insert = $this->M_BeritaAcara->insert();
        if($insert) {
            $this->response(['status' => parent::HTTP_OK, 'message' => $insert]);
        }else {
            $this->send_error('failed');
        }
    }

    public function getBa_get()
    {
        $data = $this->M_BeritaAcara->getBa();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

    public function getDetailBA_get($id)
    {
        $data = $this->M_BeritaAcara->getBy($id);
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }
}