<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminModel;

class AdminController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $model = new AdminModel();
        $data = $model->findAll();

        if (!empty($data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $data
            ]);
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'No data found',
                'data' => []
            ]);
        }
    }

    public function create()
    {
        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        helper(['form']);
        $rules = [
            'nama' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getVar('nama'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        ];

        $model = new AdminModel();
        $model->insert($data);

        $response = [
            'status' => 201, // 201 Created
            'error' => null,
            'messages' => [
                'success' => 'Data Inserted'
            ]
        ];

        return $this->respondCreated($response);
    }

    public function deleteUser($id = null)
    {
        $model = new AdminModel();
        $admin = $model->find($id);

        if ($admin) {
            $proses = $model->delete($id);

            if ($proses) {
                $response = [
                    'status' => 200,
                    'messages' => 'Data berhasil dihapus',
                ];
            } else {
                $response = [
                    'status' => 500, // Internal Server Error
                    'messages' => 'Gagal menghapus data',
                ];
            }

            return $this->respond($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }

    public function update($id = null)
    {
        $model = new AdminModel();
        $admin = $model->find($id);

        if ($admin) {
            $data = [
                'nama' => $this->request->getVar('nama'),
                'email' => $this->request->getVar('email'),
                // If you want to update the password as well, include it here.
            ];

            $model->update($id, $data);

            $response = [
                'status' => 200,
                'messages' => 'Data berhasil diupdate',
            ];

            return $this->respond($response);
        } else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
}
