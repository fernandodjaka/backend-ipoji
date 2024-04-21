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
            // User not found
            return $this->respond(['message' => 'Akun belum didaftarkan'], 401);
        }

        // Check password
        $pwd_verify = password_verify($password, $user['password']);

        if (!$pwd_verify) {
            // Password is incorrect
            return $this->respond(['message' => 'Login failed, incorrect password'], 401);
        }

        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;

        $payload = [
            "iss" => $user['id'],  // ID pengguna
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $user['email'],
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'code' => 200,
            'status' => 'success',
            'messages' => 'Login successfully',
            'token' => $token,
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
        ];

        return $this->respond($response);
    }

    public function getUserData()
    {
        $key = getenv('JWT_SECRET');
        $header = $this->request->getHeaderLine("Authorization");
        $token = null;

        // extract the token from the header
        if (!empty($header)) {
            if (preg_match('/Bearer\s+(.*)$/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        // check if token is null or empty
        if (is_null($token) || empty($token)) {
            return $this->respond(['error' => 'Access denied'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Ambil informasi pengguna dari token
            $iss = $decoded->iss;

            // Dapatkan data pengguna dari database
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
