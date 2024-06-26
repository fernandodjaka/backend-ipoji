<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\AdminModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AdminController extends BaseController
{
    use ResponseTrait;

    protected $format = 'json';

    public function index()
    {
        $adminModel = new AdminModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $admin = $adminModel->where('email', $email)->first();

        if (is_null($admin)) {
            // Admin not found
            return $this->respond(['message' => 'Akun admin belum didaftarkan'], 401);
        }

        // Check password
        $pwd_verify = password_verify($password, $admin['password']);

        if (!$pwd_verify) {
            // Password is incorrect
            return $this->respond(['message' => 'Login admin failed, incorrect password'], 401);
        }

        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;

        $payload = [
            "iss" => $admin['id'],  // ID admin
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $admin['email'],
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'code' => 200,
            'status' => 'success',
            'messages' => 'Login admin successfully',
            'token' => $token,
            'admin_id' => $admin['id'],
            'admin_name' => $admin['name'],
            'admin_email' => $admin['email'],
        ];

        return $this->respond($response);
    }

    public function getAdminData()
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

            // Ambil informasi admin dari token
            $iss = $decoded->iss;

            // Dapatkan data admin dari database
            $adminModel = new AdminModel();
            $adminData = $adminModel->where('id', $iss)->first();

            if ($adminData) {
                return $this->respond($adminData);
            } else {
                return $this->failNotFound('Admin not found');
            }
        } catch (\Exception $ex) {
            return $this->respond(['error' => 'Access denied'], 401);
        }
    }
}
