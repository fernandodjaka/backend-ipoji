<?php

// app/Controllers/WilayahController.php


namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class WilayahController extends BaseController
{
    private $baseUri = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    private function fetchApi($url)
    {
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($url);

            if ($response->getStatusCode() !== ResponseInterface::HTTP_OK) {
                log_message('error', 'Error fetching data from API. Status Code: ' . $response->getStatusCode());
                return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                                      ->setJSON(['message' => 'Error fetching data']);
            }

            return $this->response->setStatusCode(ResponseInterface::HTTP_OK)
                                  ->setJSON(json_decode($response->getBody()));
        } catch (\Exception $e) {
            log_message('error', 'Exception occurred: ' . $e->getMessage());
            return $this->response->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                                  ->setJSON(['message' => 'Error fetching data']);
        }
    }

    public function getProvinces()
    {
        return $this->fetchApi("{$this->baseUri}/provinces.json");
    }

    public function getRegencies($provinceId)
    {
        return $this->fetchApi("{$this->baseUri}/regencies/{$provinceId}.json");
    }

    public function getDistricts($regencyId)
    {
        return $this->fetchApi("{$this->baseUri}/districts/{$regencyId}.json");
    }

    public function getVillages($districtId)
    {
        return $this->fetchApi("{$this->baseUri}/villages/{$districtId}.json");
    }

    public function getProvinceById($provinceId)
    {
        return $this->fetchApi("{$this->baseUri}/province/{$provinceId}.json");
    }

    public function getRegencyById($regencyId)
    {
        return $this->fetchApi("{$this->baseUri}/regency/{$regencyId}.json");
    }

    public function getDistrictById($districtId)
    {
        return $this->fetchApi("{$this->baseUri}/district/{$districtId}.json");
    }

    public function getVillageById($villageId)
    {
        return $this->fetchApi("{$this->baseUri}/village/{$villageId}.json");
    }
}