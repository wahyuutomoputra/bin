<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends API_Controller {

	public function __construct()
	{
		parent::__construct();
    }

	public function index()
	{
		$this->response(['status' => parent::HTTP_NOT_FOUND, 'message' => 'Not found!']);
	}

}
