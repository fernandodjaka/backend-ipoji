<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Ongkir extends Controller
{
    public function index()
    {
        // Jangan echo HTML, kirim respons JSON
        return $this->response->setJSON([
            'message' => 'Welcome to Ongkir API'
        ]);
    }

    public function getKota()
    {
        // Tambahkan kunci API Anda di sini
        $apiKey = "77f24e2edd49b1e23c318f8170fa7456";

        // Lakukan permintaan ke API RajaOngkir
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

    public function cekOngkir()
    {
        // Logika untuk menghitung biaya pengiriman
    }
}
