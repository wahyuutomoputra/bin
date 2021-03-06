<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konten extends API_Controller {

    public function __construct()
	{
		parent::__construct();
        $this->auth();
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

    public function cek_post()
    {
        print_r($_FILES['file']);

        $idx = 0;
        foreach ($_FILES['file']['name'] as   $key=>$val) {
            // echo $key['name'][$idx];
            // $idx++;
            print_r($_FILES['file']['name'][$key]);
            
        }
    }

    public function getBy_get()
    {
        $data = $this->M_Konten->laporanBulanan()->result();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }
    

    public function getAll_get()
    {
        if(isset($_GET['tahun']) ){
            $data = $this->M_Konten->getByYear()->result();
        } else {
            $data = $this->M_Konten->getWithTag()->result();
        }

        $this->responseKonten($data);
    }

    public function getByKategori_post()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $data = $this->M_Konten->getByKategori($input)->result();

        $this->responseKonten($data);
    }

    public function yearList_get()
    {
        $data = $this->M_Konten->getYearList()->result(); 
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

    public function responseKonten($data)
    {
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
            //$konten['document'] = $value->document;
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

    public function docInsert_post()
    {
        $data = $this->M_Konten->docInsert();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

    public function getDoc_get($id)
    {
        $data = $this->db->get_where('uploads', array('id_konten' => $id))->result();
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
            $data =  $this->M_Konten->update();
            
            $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
        } else {
            $this->response(['status' => parent::HTTP_NOT_FOUND, 'message' => 'data tidak lengkap']);
        }
    }

    public function updateWithoutFoto_post()
    {
        $data = $this->M_Konten->withoutFoto();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

    public function delete_get() 
    {
        $data = $this->M_Konten->delete();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

    public function getByCountry_get()
    {
        $data = $this->M_Konten->getByCountry()->result();
        
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
            $konten['document'] = $value->document;
            $konten['country'] = $this->M_Konten->getCountry($value->id);

            $isi[] = $konten;
        }
        $this->response(['status' => parent::HTTP_OK, 'data' => $isi]);
    }

}

?>