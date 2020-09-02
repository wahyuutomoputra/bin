<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_User extends CI_Model {

    public function insert($data)
    {
        return $this->db->insert('user', $data);
    }

    public function login($email)
    {
        //return $this->db->get_where('user', array('username' => $email));
        $query = $this->db->query("
                SELECT
                    u.*,
                    c.NAME as countryName
                FROM
                    user u
                    JOIN country c ON u.negara = c.iso 
                WHERE
                    u.username = '".$email."'
        ");

        return $query;
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
    }

    public function get()
    {
        return $this->db->query("
        SELECT
            u.*,
            c.NAME AS countryName 
        FROM
            user u
            JOIN country c 
        WHERE
            u.negara = c.iso
        ");
    }

    public function getBy()
    {
        //return $this->db->get_where('user', array('id' => $this->input->get('id')));
        return $this->db->query("
        SELECT
            u.*,
            c.NAME AS countryName 
        FROM
            user u
            JOIN country c 
        WHERE
            u.negara = c.iso
            AND 
            u.id = ".$this->input->get('id')."
        ");
    }

    public function getUsername($username)
    {
        return $this->db->get_where('user', array('username' => $username));
    }

    public function delete()
    {
        return $this->db->delete('user', array('id' => $this->input->get('id')));
    }

    public function getTotal()
    {
        $user = $this->db->query("Select count(*) as user from user")->row_array();
        $berita = $this->db->query("select count(*) as berita from konten")->row_array();
        $isu = $this->db->query("
            SELECT
                COUNT( * ) as isu
            FROM
                konten 
            WHERE
                kategori IN ( 'Separatisme', 'Kejahatan Lintas Batas', 'Terorisme', 'PWNI' )
        ")->row_array();
        $bdi = $this->db->query("select count(*) as bdi from konten where kategori = 'BDI'")->row_array();
        $bulan = $this->db->query("select count(*) as bulan from konten where kategori = 'Laporan Bulanan'")->row_array();

        return [
            'total_user' => $user['user'],
            'total_berita' => $berita['berita'],
            'isu_bersama' => $isu['isu'],
            'bdi' => $bdi['bdi'],
            'laporan_bulanan' => $bulan['bulan']
        ];
    }

    public function resetPass($user)
    {
        $res = 'Failed';
        $config = [
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_user' => 'utomoputraw@gmail.com',  
            'smtp_pass'   => 'pusamania94', 
            'smtp_crypto' => 'ssl',
            'smtp_port'   => 465,
            'crlf'    => "\r\n",
            'newline' => "\r\n"
        ];

        $data = $this->db->get_where('user', array('username' => $user))->row_array();
        $key = $this->generateRandomString();

        $updt = array('password' => password_hash($key, PASSWORD_DEFAULT));

        $this->load->library('email', $config);
        $this->email->from('no-reply@bin.com', 'One Mission');
        $this->email->to($data['email']);
        $this->email->subject('Reset Password');
        $this->email->message("Your new password is: ".$key);
        if($this->email->send()){
            $this->db->where('id', $data['id']);
            $this->db->update('user', $updt);
            $res = 'Success';
        }

        return $this->email->print_debugger();
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function editPass($input)
    {
        $updt = array('password' => password_hash($input['password'], PASSWORD_DEFAULT));
        $this->db->where('id', $input['id']);
        return $this->db->update('user', $updt);
    }

}

?>