<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class RajaOngkirController extends BaseController
{
    use ResponseTrait;

    private $apiKey;

    public function __construct()
    {
        $this->apiKey = getenv('RAJAONGKIR_API_KEY'); // Pastikan API key benar di .env file
    }

    public function getProvinces()
    {
        $response = $this->callRajaOngkirAPI('https://api.rajaongkir.com/starter/province');
        if (isset($response['error'])) {
            return $this->fail($response['message']);
        }
        return $this->respond($response);
    }

    public function getCities($provinceId)
    {
        $response = $this->callRajaOngkirAPI("https://api.rajaongkir.com/starter/city?province=$provinceId");
        if (isset($response['error'])) {
            return $this->fail($response['message']);
        }
        return $this->respond($response);
    }

    public function getShippingCost()
    {
        $origin = $this->request->getPost('origin');
        $destination = $this->request->getPost('destination');
        $weight = $this->request->getPost('weight');
        $courier = $this->request->getPost('courier');

        $data = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier
        ];

        $response = $this->callRajaOngkirAPI('https://api.rajaongkir.com/starter/cost', $data);
        if (isset($response['error'])) {
            return $this->fail($response['message']);
        }
        return $this->respond($response);
    }

    private function callRajaOngkirAPI($url, $data = null)
    {
        $client = \Config\Services::curlrequest();
        $options = [
            'headers' => [
                'key' => $this->apiKey
            ]
        ];

        if ($data) {
            $options['form_params'] = $data;
        }

        try {
            $response = $client->request($data ? 'POST' : 'GET', $url, $options);
            $body = $response->getBody();
            log_message('debug', 'RajaOngkir API Response: ' . $body);
            return json_decode($body, true);
        } catch (\Exception $e) {
            log_message('error', 'RajaOngkir API Error: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}