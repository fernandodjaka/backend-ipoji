<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class RajaOngkirProxy extends BaseController
{
    use ResponseTrait;

    public function getProvince()
    {
        $client = \Config\Services::curlrequest();
        $response = $client->request('GET', 'https://api.rajaongkir.com/starter/province', [
            'headers' => [
                'key' => '77f24e2edd49b1e23c318f8170fa7456' // Ganti dengan API key Anda
            ]
        ]);

        $body = $response->getBody();

        return $this->response->setStatusCode($response->getStatusCode())->setBody($body);
    }

    public function getCity()
    {
        $client = \Config\Services::curlrequest();
        $response = $client->request('GET', 'https://api.rajaongkir.com/starter/city', [
            'headers' => [
                'key' => '77f24e2edd49b1e23c318f8170fa7456' // Ganti dengan API key Anda
            ]
        ]);

        $body = $response->getBody();

        return $this->response->setStatusCode($response->getStatusCode())->setBody($body);
    }

    public function getCost()
    {
        $client = \Config\Services::curlrequest();
        $response = $client->request('POST', 'https://api.rajaongkir.com/starter/cost', [
            'headers' => [
                'key' => '77f24e2edd49b1e23c318f8170fa7456' // Ganti dengan API key Anda
            ],
            'form_params' => [
                'origin' => $this->request->getVar('origin'),
                'destination' => $this->request->getVar('destination'),
                'weight' => $this->request->getVar('weight'),
                'courier' => $this->request->getVar('courier')
            ]
        ]);

        $body = $response->getBody();

        return $this->response->setStatusCode($response->getStatusCode())->setBody($body);
    }
}
