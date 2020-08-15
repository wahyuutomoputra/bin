<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends API_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['jwt', 'authorization']); 
        $this->load->model('M_User');
    }

    public function login_post()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if ( isset($input['username']) && isset($input['password']) ){
            $check = $this->M_User->login($input['username']);

            if ($check->num_rows() > 0) {
                $data = $check->row_array();
                
                if (password_verify($input['password'] ,$data['password'])) {
                    $token = AUTHORIZATION::generateToken($data);
                    $data['token'] = $token;
                    
                    $this->response(['status' => parent::HTTP_OK, 'data' => $data, 'error' => false]);
                } else {
                    $this->response(['status' => parent::HTTP_OK, 'message' => 'Password Salah', 'error' => true]);
                }

            } else {
                $this->response(['status' => parent::HTTP_OK, 'message' => 'username tidak ditemukan', 'error' => true]);
            }
            
        } else {
            $this->response(['status' => parent::HTTP_NOT_FOUND, 'message' => 'data tidak lengkap', 'error' => true]);
        }
    }

}

/* End of file Api.php */