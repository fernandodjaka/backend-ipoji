<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class LoginController extends BaseController
{
    use ResponseTrait;

    protected $format = 'json';

    public function login()
    {
        $jsonData = $this->request->getJSON();
        
        $email = $jsonData->email;
        $password = $jsonData->password;

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user === null) {
            // User not found
            $response = [
                'code' => 401,
                'status' => 'failed',
                'message' => 'Akun belum didaftarkan',
            ];
        } else {
            // Check password
            if (password_verify($password, $user['password'])) {
                // Password is correct
                $session = session();
                $session->set('user_id', $user['id']);
                $session->set('user_email', $user['email']);

                $response = [
                    'code' => 200,
                    'status' => 'success',
                    'messages' => 'Login successfully',
                    'user_id' => $user['id'],
                    'user_name' => $user['name'], 
                    'user_email' => $user['email'],
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
