<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends API_Controller {
    public function __construct()
	{
		parent::__construct();
        //$this->auth();
        $this->load->library('form_validation');
        $this->load->model('M_BeritaAcara');
    }
}