<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class Ongkir extends Controller {

    public function index() {
        // Jangan echo HTML, kirim respons JSON
        return $this->response->setJSON([
            'message' => 'Welcome to Ongkir API'
        ]);
    }

    public function getKota() {
        $apiKey = "0c47f3bf73d0910ebc152bcb224e2ad9"; // Masukkan kunci API di sini

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: $apiKey" // Menggunakan kunci API yang diberikan
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        // Kembalikan data sebagai respons JSON
        return $this->response->setJSON($data);
    }

    public function cekOngkir() {
        $apiKey = "0c47f3bf73d0910ebc152bcb224e2ad9"; // Masukkan kunci API di sini

        $kota_asal = $this->request->getPost('kota_asal');
        $kota_tujuan = $this->request->getPost('kota_tujuan');
        $kurir = $this->request->getPost('kurir');
        $berat = $this->request->getPost('berat') * 1000;

        // Lakukan permintaan ke API RajaOngkir
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$kota_asal&destination=$kota_tujuan&weight=$berat&courier=$kurir",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: $apiKey" // Menggunakan kunci API yang diberikan
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        // Format dan kembalikan data sebagai respons JSON
        return $this->response->setJSON($data);
    }
}
