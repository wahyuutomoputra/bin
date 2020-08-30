<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'third_party/REST_Controller.php';
require APPPATH . 'third_party/Format.php';

use Restserver\Libraries\REST_Controller;

//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class API_Controller extends REST_Controller {
    public function __construct()
	  {
      parent::__construct();
      if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
          header('Access-Control-Allow-Origin: *');
          header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
          header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, token");
          header('Access-Control-Max-Age: 1728000');
          header('Content-Length: 0');
          header('Content-Type: text/plain');
          header('Access-Control-Allow-Credentials: true');
          header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');
          die();
      }

      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Credentials: true');
      header("Access-Control-Allow-Methods: PUT, GET, POST");
      header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
      header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');
      header('Content-Type: application/json');
    }
    
    public function auth()
    {
      if(!$this->login->check($this->input->request_headers())) {
			  $this->response($this->login->unauthorized()); exit;
		  }
    }

    public function send_error($data)
    {
      $this->response(['status' => parent::HTTP_UNPROCESSABLE_ENTITY, 'message' => $data, 'error' => true]);
    }
    
}
?>