<?php

class Login   
{
    public function check($headers)
    {
        $status = FALSE;

        if (array_key_exists('token', $headers) && !empty($headers['token'])) {

            try {
                $data = AUTHORIZATION::validateToken($headers['Authorization']);
                $status = $data ? TRUE : FALSE;
            } catch (Exception $e) {
                return $status;
            }
        }

        return $status;
    }

    public function unauthorized()
    {
        return ['status' => 403, 'msg' => 'Unauthorized Access!'];
    }

    public function decode($token)
    {
        $notFound = ['message' => 'Token not found!'];

        try {
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                return $notFound;
            } else {
                return $data;
            }
        } catch (Exception $e) {
            return $notFound;
        }
    }
}

?>