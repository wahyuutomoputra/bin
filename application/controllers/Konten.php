<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konten extends API_Controller {

    public function __construct()
	{
		parent::__construct();
        //$this->auth();
        $this->load->model('M_Konten');
    }

    public function tes_post()
    {
        $tag = $_POST['Alltag'];
        $data = json_decode($tag, true);
        print_r($data);
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
        $data = $this->M_Konten->getWithTag()->result();

        $isi = array();
        foreach ($data as $value) {
            $konten['id'] = $value->id;
            $konten['heading'] = $value->heading;
            $konten['sub_heading'] = $value->sub_heading;
            $konten['informasi'] = $value->informasi;
            $konten['foto'] = $value->foto;
            $konten['tags'] = json_decode($value->tags);
            $konten['createdAt'] = $value->createdAt;
            $konten['kategori'] = $value->kategori;
            $konten['caption'] = $value->caption;
            $konten['country'] = $this->M_Konten->getCountry($value->id);

            $isi[] = $konten;
        }
        $this->response(['status' => parent::HTTP_OK, 'data' => $isi]);
    }

    public function getKontenBy_get()
    {
        $data = $this->M_Konten->getById()->result();
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

    public function getByCountry_get()
    {
        $data = $this->M_Konten->getByCountry()->result();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

}

?>