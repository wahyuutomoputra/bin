<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends API_Controller {

	public function __construct()
	{
		parent::__construct();
        //$this->auth();
        $this->load->model('M_User');
    }

    public function insertUser_post()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if(
            isset($input['nama']) &&
            isset($input['nik']) &&
            isset($input['username']) &&
            isset($input['negara']) &&
            isset($input['password']) &&
            isset($input['status'])
        ) {

            $data = array(
                'nama' => $input['nama'],
                'nik' => $input['nik'],
                'username' => $input['username'],
                'negara' => $input['negara'],
                'password' => password_hash($input['password'], PASSWORD_DEFAULT),
                'createdAt' => date('y-m-d'),
                'status' => $input['status']
            );

            
            try { 
                $cek = $this->M_User->getUsername($input['username']);
                
                if($cek->num_rows() >= 1) {
                    $this->send_error('username telah digunakan');
                } else {
                    $insert = $this->M_User->insert($data);

                    if($input) {
                        $this->response(['status' => parent::HTTP_OK, 'message' => 'created', 'data' => $data]);
                    }else {
                        $this->send_error('failed');
                    }
                }
            }
            catch(Exception $e) {
                $this->send_error($e->getMessage());
            }

        } else { 
            $this->response(['status' => parent::HTTP_NOT_FOUND, 'message' => 'data tidak lengkap']);
        }
        
    }

    public function updateUser_post()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if(
            isset($input['nama']) &&
            isset($input['nik']) &&
            isset($input['username']) &&
            isset($input['negara']) &&
            isset($input['status']) &&
            isset($input['id'])
        ) {

            $data = array(
                'nama' => $input['nama'],
                'nik' => $input['nik'],
                'username' => $input['username'],
                'negara' => $input['negara'],
                'createdAt' => date('y-m-d'),
                'status' => $input['status'],
            );

            
            try { 
                $insert = $this->M_User->update($input['id'], $data);

                if($input) {
                    $this->response(['status' => parent::HTTP_OK, 'message' => 'created', 'data' => $data]);
                }else {
                    $this->send_error('failed');
                }
            }
            catch(Exception $e) {
                $this->send_error($e->getMessage());
            }

        } else { 
            $this->response(['status' => parent::HTTP_NOT_FOUND, 'message' => 'data tidak lengkap']);
        }

    }

    public function getUser_get()
    {
        $data = $this->M_User->get()->result();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);

    }

    public function getUserBy_get()
    {
        $data = $this->M_User->getBy()->result();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);

    }

    public function deleteUser_get()
    {
        $data = $this->M_User->delete();
        $this->response(['status' => parent::HTTP_OK, 'data' => $data]);
    }

	
}
