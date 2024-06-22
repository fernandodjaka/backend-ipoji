<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class UserController extends ResourceController
{
    protected $format = 'json';

    public function create()
    {
        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        $model = new UserModel();
        $existingUser = $model->where('email', $this->request->getVar('email'))->first();
        if ($existingUser) {
            return $this->fail('Email sudah digunakan', 409);
        }

        helper(['form']);
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'name' => 'required',
            'role' => 'required|in_list[admin,penjual,user]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'name' => $this->request->getVar('name'),
            'role' => $this->request->getVar('role')
        ];

        $model->insert($data);

        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => "Data Inserted"
            ]
        ];

        return $this->respondCreated($response);
    }
}
