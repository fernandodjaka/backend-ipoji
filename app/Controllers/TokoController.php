<?php

namespace App\Controllers;

use App\Models\PenjualModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class TokoController extends ResourceController
{
    protected $format = 'json';

    public function index()
{
    $model = new PenjualModel(); // Ganti UserModel dengan nama model yang sesuai
    $data = $model->findAll(); // Mengambil data dari model, sesuaikan dengan metode yang sesuai

    $userCount = count($data);

    if (!empty($data)) {
        $response = [
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'total_users' => $userCount, // Menambahkan jumlah pengguna ke respons
            'data' => $data
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No data found',
            'total_users' => 0, // Menambahkan jumlah pengguna ke respons dengan nilai default 0
            'data' => []
        ];
    }

    return $this->respond($response);
}

    public function create() {
        if ($this->request->getMethod() === 'options') {
            // Handle OPTIONS request (tidak perlu validasi atau penyimpanan data)
            return $this->response->setStatusCode(200);
        }
    
        helper(['form']);
        $rules = [
            'email' => 'required',
            'password' => 'required',
            'name' => 'required'
        ];
    
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
    
        $data = [
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'name' => $this->request->getVar('name')
        ];
    
        $model = new PenjualModel();
        $model->insert($data);
    
        $response = [
            'status' => 200, // Change status code to 200 (Created)
            'error' => null,
            'messages' => [
                'success' => "Data Inserted"
            ]
        ];
    
        return $this->respondCreated($response);
    }
}