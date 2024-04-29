<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class Kota extends Controller {

    public function get_kota($q) {
        $apiKey = "0c47f3bf73d0910ebc152bcb224e2ad9"; // Ganti dengan kunci API Anda

        // Membuat array untuk menampung data kota
        $results = [];

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

        // Mengisi array results dengan data kota
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
