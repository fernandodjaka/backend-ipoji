<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\PenjualModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PenjualController extends BaseController
{
    use ResponseTrait;

    protected $format = 'json';

    public function index()
    {
        $penjualModel = new PenjualModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $penjual = $penjualModel->where('email', $email)->first();

        if (is_null($penjual)) {
            // User not found
            return $this->respond(['message' => 'Akun belum didaftarkan'], 401);
        }

        // Check password
        $pwd_verify = password_verify($password, $penjual['password']);

        if (!$pwd_verify) {
            // Password is incorrect
            return $this->respond(['message' => 'Login failed, incorrect password'], 401);
        }

        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;

        $payload = [
            "iss" => $penjual['id'],  // ID pengguna
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $penjual['email'],
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'code' => 200,
            'status' => 'success',
            'messages' => 'Login successfully',
            'token' => $token,
            'penjual_id' => $penjual['id'],
            'penjual_name' => $penjual['name'],
            'penjual_email' => $penjual['email'],
        ];

        return $this->respond($response);
    }

    public function getPenjualData()
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
            $penjualModel = new PenjualModel();
            $penjualData = $penjualModel->where('id', $iss)->first();

            if ($penjualData) {
                return $this->respond($penjualData);
            } else {
                return $this->failNotFound('User not found');
            }
        } catch (\Exception $ex) {
            return $this->respond(['error' => 'Access denied'], 401);
        }
    }
}
