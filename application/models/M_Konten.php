<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Konten extends CI_Model {

    public function insert()
    {
        $this->load->library('upload');

        $foto                           = str_replace(' ', '', $_FILES['foto']['name']);
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 5000;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $foto;

        $this->upload->initialize($config);
        if(!$this->upload->do_upload('foto')) {
            $error = array('error' => $this->upload->display_errors());
            return ['error' => true, 'errorMessage' => $error];
        }

        

        $data = array(
            'heading' => $this->input->post('heading'),
            'sub_heading' => $this->input->post('sub_heading'),
            'informasi' => $this->input->post('informasi'),
            'caption' => $this->input->post('caption'),
            'createdAt' => date('Y-m-d h:i:s'),
            'foto' => $foto,
            'kategori' => $this->input->post('kategori')
        );

        $this->db->insert('konten', $data);
        $insert_id = $this->db->insert_id();

        $tag = json_decode($_POST['Alltag'], true);
        foreach ($tag as $value) {
            $this->db->insert('tag', array('idKonten' => $insert_id, 'name' => $value['name']));
        }

        $data['id_konten'] = $insert_id;

        // $insertDoc = $this->insertDoc($_FILES['file'], $insert_id);
        // $files = $_FILES;
        // $cpt = count($_FILES['file']['name']);
        // for($i=0; $i<$cpt; $i++)
        // {   echo $files['file']['name'][$i];
        //     //$a = $files['file']['name'][$i];
        //     $_FILES['file']['name']= $files['file']['name'][$i];
        //     $_FILES['file']['type']= $files['file']['type'][$i];
        //     $_FILES['file']['tmp_name']= $files['file']['tmp_name'][$i];
        //     $_FILES['file']['error']= $files['file']['error'][$i];
        //     $_FILES['file']['size']= $files['file']['size'][$i];    

        //     $this->upload->initialize($this->set_upload_options());
        //     $this->upload->do_upload();
        //     $this->db->insert('uploads', array('id_konten' => $id, 'name' => 'a'));
        // }

        $listCountry = json_decode($_POST['country'], true);
        foreach ($listCountry as $value) {
            $this->db->insert('konten_privileges', array('konten_id' => $insert_id, 'country_iso' => $value['name']));
        }

        return $data;
    }

    private function set_upload_options()
    {   
        //upload an image options
        $config = array();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';

        return $config;
    }

    public function docInsert()
    {
        $this->load->library('upload');

        $foto                           = str_replace(' ', '', $_FILES['file']['name']);
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = '*';
        $config['max_size']             = 5000;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $foto;

        $this->upload->initialize($config);
        if(!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            return ['error' => true, 'errorMessage' => $error];
        }

        return $this->db->insert('uploads', array('id_konten' => $_POST['id_konten'], 'nama_file' => $foto));
    }

    public function insertDoc($param, $id)
    {
        $this->load->library('upload');

        $idx = 0;
        foreach ($param['name'] as $key => $value) {
            //$doc                            = str_replace(' ', '', $param['name'][$key]);
            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = '*';
            $config['max_size']             = 5000;
            //$config['file_name']            = $doc;

            $this->upload->initialize($config);
            if(!$this->upload->do_upload($param['tmp_name'][$key])) {
                $error = array('error' => $this->upload->display_errors());
                return ['error' => true, 'errorMessage' => $error];
            }

            $this->db->insert('uploads', array('id_konten' => $id, 'name' => $param['name'][$key]));

            $idx++;
        }

    }

    public function update()
    {
        $this->load->library('upload');

        $foto                           = str_replace(' ', '', $_FILES['foto']['name']);
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $config['file_name']            = $foto;

        $this->upload->initialize($config);
        if(!$this->upload->do_upload('foto')) {
            $error = array('error' => $this->upload->display_errors());
            return ['error' => true, 'errorMessage' => $error];
        }

        $data = array(
            'heading' => $this->input->post('heading'),
            'sub_heading' => $this->input->post('sub_heading'),
            'informasi' => $this->input->post('informasi'),
            'caption' => $this->input->post('caption'),
            'foto' => $foto,
            'kategori' => $this->input->post('kategori')
        );
            
       
        $this->db->where('id', $this->input->post('id'));
        $res = $this->db->update('konten', $data);

        $this->db->delete('tag', array('idKonten' => $this->input->post('id')));

        $tag = json_decode($_POST['Alltag'], true);
        foreach ($tag as $value) {
            $this->db->insert('tag', array('idKonten' => $this->input->post('id'), 'name' => $value['name']));
        }

        return $res;
    }

    public function withoutFoto()
    {
        $data = array(
            'heading' => $this->input->post('heading'),
            'sub_heading' => $this->input->post('sub_heading'),
            'informasi' => $this->input->post('informasi'),
            'caption' => $this->input->post('caption'),
            'createdAt' => date('Y-m-d h:i:s'),
            'kategori' => $this->input->post('kategori')
        );

        $this->db->where('id', $this->input->post('id'));
        $res = $this->db->update('konten', $data);

        $this->db->delete('tag', array('idKonten' => $this->input->post('id')));

        $tag = json_decode($_POST['Alltag'], true);
        foreach ($tag as $value) {
            $this->db->insert('tag', array('idKonten' => $this->input->post('id'), 'name' => $value['name']));
        }

        return $res;
    }

    public function get()
    {
        return $this->db->get('konten');
    }

    public function delete()
    {
        return $this->db->delete('konten', array('id' => $this->input->get('id')));
    }

    public function getWithTag()
    {
        // $query = $this->db->query("
        //             SELECT
        //                 k.*,
        //                 CAST( CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'tagId', t.id, 'name', t.NAME ) ), ']' ) AS JSON ) AS tags 
        //             FROM
        //                 konten k
        //                 LEFT JOIN tag t ON k.id = t.idKonten 
        //             GROUP BY
        //                 k.id
        // ");

        $query = $this->db->query('
                SELECT
                    k.*,
                    CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
                FROM
                    konten k
                    LEFT JOIN tag p ON k.id = p.idKonten 
                GROUP BY
                    k.id
                ORDER BY
	                k.createdAt DESC
        ');

        return $query;
    }

    public function getByYear()
    {
        $year = $this->input->get('tahun');
        // $country = $this->input->get('country');
        // $category = $this->input->get('category');

        $query = $this->db->query('
                SELECT
                    k.*,
                    CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
                FROM
                    konten k
                    LEFT JOIN tag p ON k.id = p.idKonten 
                    WHERE YEAR(k.createdAt ) = "'.$year.'"
                GROUP BY
                    k.id
                ORDER BY
                    k.createdAt DESC
        ');

        // $query = $this->db->query('
        //         SELECT
        //             k.*,
        //             CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
        //         FROM
        //             konten k
        //             LEFT JOIN tag p ON k.id = p.idKonten 
        //             WHERE
        //             k.id IN ( SELECT k.id FROM konten k JOIN konten_privileges kp ON ( k.id = kp.konten_id ) WHERE kp.country_iso = "'.$country.'" ) 
        //             AND k.kategori = "'.$category.'" AND YEAR(k.createdAt ) = "'.$year.'"
        //         GROUP BY
        //             k.id
        // ');

        return $query;
    }

    public function getYearList()
    {
        $sql = "SELECT YEAR(createdAt) as tahun FROM konten GROUP BY YEAR(createdAt)";
        return $this->db->query($sql);
    }

    public function laporanBulanan()
    {
        $tahun = $this->input->get('tahun');
        $year = str_replace('%20', ' ', $tahun);
        $country = $this->input->get('country');
        $category = $this->input->get('category');

        $query = $this->db->query('
                SELECT
                    k.*,
                    CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
                FROM
                    konten k
                    LEFT JOIN tag p ON k.id = p.idKonten 
                    WHERE
                    k.id IN ( SELECT k.id FROM konten k JOIN konten_privileges kp ON ( k.id = kp.konten_id ) WHERE kp.country_iso = "'.$country.'" ) 
                    AND k.kategori = "'.$category.'" AND YEAR(k.createdAt ) = "'.$year.'"
                GROUP BY
                    k.id
        ');

        return $query;
    }

    public function getByKategori($input)
    {
        // $query = $this->db->query('
        //         SELECT
        //             k.*,
        //             CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
        //         FROM
        //             konten k
        //             LEFT JOIN tag p ON k.id = p.idKonten 
        //             WHERE k.kategori = "'.$kategori.'"
        //         GROUP BY
        //             k.id
        //         ORDER BY
        //             k.createdAt DESC
        // ');

        $kategori = $input['kategori'];
        $country = $input['country'];

        $query = $this->db->query('
                SELECT
                    k.*,
                    CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
                FROM
                    konten k
                    LEFT JOIN tag p ON k.id = p.idKonten 
                    WHERE
                    k.id IN ( SELECT k.id FROM konten k JOIN konten_privileges kp ON ( k.id = kp.konten_id ) WHERE kp.country_iso = "'.$country.'" ) 
                    AND k.kategori = "'.$kategori.'"
                GROUP BY
                    k.id
        ');

        return $query;
    }

    public function getCountry($id)
    {
        return $query = $this->db->query("
                SELECT
                    * 
                FROM
                    konten_privileges kp
                    JOIN country c ON kp.country_iso = c.iso 
                WHERE
                    konten_id = ".$id."
        ")->result();
    }

    public function getById()
    {
        $id = $this->input->get('id');
        // $query = $this->db->query("
        //             SELECT
        //                 k.*,
        //                 CAST( CONCAT( '[', GROUP_CONCAT( JSON_OBJECT( 'tagId', t.id, 'name', t.NAME ) ), ']' ) AS JSON ) AS tags 
        //             FROM
        //                 konten k
        //                 LEFT JOIN tag t ON k.id = t.idKonten 
        //                 WHERE k.id = '".$id."'
        //             GROUP BY
        //                 k.id
        // ");

        $query = $this->db->query('
                    SELECT
                    k.*,
                    CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
                FROM
                    konten k
                    LEFT JOIN tag p ON k.id = p.idKonten 
                    WHERE k.id = '.$id.'
                GROUP BY
                    k.id
        ');

        return $query;
    }

    public function getByCountry()
    {
        $country = $this->input->get("country");
        

        if(isset($_GET['year'])){
            $year = $_GET['year'];

            $query = $this->db->query('
                SELECT
                    k.*,
                    CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
                FROM
                    konten k
                    LEFT JOIN tag p ON k.id = p.idKonten 
                    WHERE
                    k.id IN ( SELECT k.id FROM konten k JOIN konten_privileges kp ON ( k.id = kp.konten_id ) WHERE kp.country_iso = "'.$country.'" ) 
                    AND YEAR(k.createdAt ) = "'.$year.'"
                GROUP BY
                    k.id
                order by k.createdAt desc
            ');

        } else {
            $query = $this->db->query('
                SELECT
                    k.*,
                    CONCAT( "[", GROUP_CONCAT( CONCAT( "{\"id\":", p.id, ",\"name\":\"", p.NAME, "\"}" ) ), "]" ) tags 
                FROM
                    konten k
                    LEFT JOIN tag p ON k.id = p.idKonten 
                    WHERE
                    k.id IN ( SELECT k.id FROM konten k JOIN konten_privileges kp ON ( k.id = kp.konten_id ) WHERE kp.country_iso = "'.$country.'" ) 
                GROUP BY
                    k.id
                order by k.createdAt desc
            ');
        }

        

        

        return $query;
    }

    

    public function getBy()
    {
        return $this->db->get_where('konten', array('id' => $this->input->get('id')));
    }
}