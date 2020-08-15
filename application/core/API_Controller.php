<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';

use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class API_Controller extends REST_Controller {
    public function __construct()
	  {
		  parent::__construct();
    }
    
    public function auth()
    {
      if(!$this->login->check($this->input->request_headers())) {
			  $this->response($this->login->unauthorized()); exit;
		  }
    }

    public function send_error($data)
    {
      $this->response(['status' => parent::HTTP_UNPROCESSABLE_ENTITY, 'message' => $data]);
    }
    
}
?>