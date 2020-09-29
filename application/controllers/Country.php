<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends API_Controller {
    public function __construct()
	{
		parent::__construct();
        $this->auth();
    }

    public function getCountry_get()
    {
        $data = $this->db->get('country')->result();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }
}