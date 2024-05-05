<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Kota extends Controller
{
    public function get_kota($q)
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

        // Buat array untuk menampung data kota
        $results = [];

        // Isi array results dengan data kota
        foreach ($data['rajaongkir']['results'] as $city) {
            $results[] = [
                'city_id' => $city['city_id'],
                'city_name' => $city['city_name']
            ];
        }

        // Kembalikan data sebagai respons JSON
        return $this->response->setJSON(['results' => $results]);
    }
}
