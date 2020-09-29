<?php

class Login   
{
    public function check($headers)
    {
        $status = FALSE;

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {

            try {
                $token = explode(" ",$headers['Authorization']);
                $data = AUTHORIZATION::validateToken($token[1]);
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