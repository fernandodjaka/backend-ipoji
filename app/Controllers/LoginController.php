<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginController extends BaseController
{
    use ResponseTrait;

    protected $format = 'json';

    public function index()
    {
        $userModel = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $userModel->where('email', $email)->first();

        if (is_null($user)) {
            return $this->respond(['message' => 'Akun belum didaftarkan'], 401);
        }

        $pwd_verify = password_verify($password, $user['password']);

        if (!$pwd_verify) {
            return $this->respond(['message' => 'Login failed, incorrect password'], 401);
        }

        $key = getenv('JWT_SECRET');
        if (!$key || !is_string($key)) {
            return $this->respond(['message' => 'Invalid JWT secret key'], 500);
        }

        $iat = time(); // Waktu saat token dikeluarkan
        $exp = $iat + 3600; // Token berlaku selama 1 jam

        $payload = [
            "iss" => $user['id'],
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat,
            "exp" => $exp,
            "email" => $user['email'],
            "role" => $user['role']  // Tambahkan role jika ada
        ];

        // Encode token JWT
        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'code' => 200,
            'status' => 'success',
            'messages' => 'Login successfully',
            'token' => $token,
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
        ];

        return $this->respond($response);
    }

    public function getUserData()
    {
        $key = getenv('JWT_SECRET');
        if (!$key || !is_string($key)) {
            return $this->respond(['message' => 'Invalid JWT secret key'], 500);
        }

        $header = $this->request->getHeaderLine("Authorization");
        $token = null;

        if (!empty($header)) {
            if (preg_match('/Bearer\s+(.*)$/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        if (is_null($token) || empty($token)) {
            return $this->respond(['error' => 'Access denied'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $iss = $decoded->iss;

            $userModel = new UserModel();
            $userData = $userModel->where('id', $iss)->first();

            if ($userData) {
                return $this->respond($userData);
            } else {
                return $this->failNotFound('User not found');
            }
        } catch (\Exception $ex) {
            return $this->respond(['error' => 'Access denied'], 401);
        }
    }
}
