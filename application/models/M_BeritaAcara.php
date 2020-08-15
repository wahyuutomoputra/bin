<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_BeritaAcara extends CI_Model {

    public function insert()
    {
        $selisih = $this->hitung_selisih($this->input->post('informasiDiterima').":00",$this->input->post('tibaDilokasi').":00");
        $this->load->library('upload');
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 5000;
        $config['max_height']           = 5000;
        $namaGambar = array("index0");
        $date = date_create();
        
        for ($i=1; $i <= 2; $i++) {
            $namaBaru = date_format($date, 'U').",,".preg_replace('/\s+/', '', $_FILES['gambar'.$i]['name']);
            array_push($namaGambar,$namaBaru);
            $config['file_name'] = $namaBaru;
            $this->upload->initialize($config);
            if(!$this->upload->do_upload('gambar'.$i)) {
                $error = array('error' => $this->upload->display_errors());
                return ['status' => false, 'error' => $error];
            }
        }

        $data = array(
            'informasiDiterima' => $this->input->post('informasiDiterima'),
            'tibaDilokasi' => $this->input->post('tibaDilokasi'),
            'selesaiPemadaman' => $this->input->post('selesaiPemadaman'),
            'responTime' => $selisih,
            'tanggal' => $this->input->post('tanggal'),
            'rt' => $this->input->post('rt'),
            'rw' => $this->input->post('rw'),
            'kampung' => $this->input->post('kampung'),
            'desa' => $this->input->post('desa'),
            'idKecamatan' => $this->input->post('kecamatan'),
            'kota' => $this->input->post('kota'),
            'namaPemilik' => $this->input->post('namaPemilik'),
            'jumlahPenghuni' => $this->input->post('jumlahPenghuni'),
            'jenisBangunan' => $this->input->post('jenisBangunan'),
            'areaTerbakar' => $this->input->post('areaTerbakar'),
            'luasArea' => $this->input->post('luasArea'),
            'penyebabKebakaran' => $this->input->post('penyebabKebakaran'),
            'asetKeseluruhan' => str_replace(".", "",$this->input->post('asetKeseluruhan')),
            'nilaiKerugian' => str_replace(".", "",$this->input->post('nilaiKerugian')),
            'asetTerselamatkan' => str_replace(".", "",$this->input->post('asetTerselamatkan')),
            'luka' => $this->input->post('luka'),
            'meninggal' => $this->input->post('meninggal'),
            'jumlahMobil' => $this->input->post('jumlahMobil'),
            'nomorPolisi' => $this->input->post('nomorPolisi'),
            'jumlahPetugas' => $this->input->post('jumlahPetugas'),
            'danru1' => $this->input->post('danru1'),
            'danru2' => $this->input->post('danru2'),
            'danton1' => $this->input->post('danton1'),
            'danton2' => $this->input->post('danton2'),
            'gambar1' => $namaGambar[1],
            'gambar2' => $namaGambar[2],
            'jenis' => $this->input->post('jenis'),
        );
        
        $insert = $this->db->insert('berita_acara', $data);
        return ['status' => $insert, 'data' => $data];
    }

    private function hitung_selisih($jam_terima,$jam_tiba)
    {

        list($h,$m,$s) = explode(":",$jam_terima);
        $dtAwal = mktime($h,$m,$s,"1","1","1");
        if (substr($jam_tiba,0,2)>= 18) {
            $waktu = mktime("18","00","00","1","1","1");
            $dtAkhir = $waktu;
        }else {
            list($h,$m,$s) = explode(":",$jam_tiba);
            $dtAkhir = mktime($h,$m,$s,"1","1","1");
        }
        $dtSelisih = $dtAkhir-$dtAwal;
        $totalmenit = $dtSelisih/60;
        $jam = explode(".",$totalmenit/60);
        $sisamenit = ($totalmenit/60)-$jam[0];
        $sisamenit2 = $sisamenit*60;
        $jml_jam = $jam[0];

        return $jml_jam." jam ".ceil($sisamenit2)." menit";
    }

    public function getBa()
    {   
        return $this->db->get('berita_acara')->result();
    }

    public function getBy($id)
    {
        return $this->db->get_where('berita_acara', array('id' => $id))->result();
    }
}