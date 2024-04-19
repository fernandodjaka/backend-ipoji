<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\AdminModel;

class LoginAdmin extends BaseController
{
    use ResponseTrait;

    protected $format = 'json';

    public function login()
    {
        $jsonData = $this->request->getJSON();
        
        $email = $jsonData->email;
        $password = $jsonData->password;

        $adminModel = new AdminModel();
        $admin = $adminModel->where('email', $email)->first();

        if ($admin === null) {
            // User not found
            $response = [
                'code' => 401,
                'status' => 'failed',
                'message' => 'Akun belum didaftarkan',
            ];
        } else {
            // Check password
            if (password_verify($password, $admin['password'])) {
                // Password is correct
                $session = session();
                $session->set('admin_id', $admin['id']);
                $session->set('admin_email', $admin['email']);

                $response = [
                    'code' => 200,
                    'status' => 'success',
                    'messages' => 'Login successfully',
                    'admin_id' => $admin['id'],
                    'admin_nama' => $admin['nama'], 
                    'admin_email' => $admin['email'],
                ];
            } else {
                // Password is incorrect
                $response = [
                    'code' => 401,
                    'status' => 'failed',
                    'messages' => 'Login failed, incorrect password',
                ];
            }
        }

        return $this->respond($response);
    }
}
