<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET');
        if (!$key || !is_string($key)) {
            log_message('error', 'Invalid JWT secret key');
            return \Config\Services::response()->setJSON(['message' => 'Invalid JWT secret key'])->setStatusCode(500);
        }

        $header = $request->getHeaderLine("Authorization");
        $token = null;

        if (!empty($header)) {
            if (preg_match('/Bearer\s+(.*)$/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        if (is_null($token) || empty($token)) {
            return \Config\Services::response()->setJSON(['error' => 'Access denied'])->setStatusCode(401);
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            if (!in_array($decoded->role, $arguments)) {
                return \Config\Services::response()->setJSON(['error' => 'Access denied'])->setStatusCode(403);
            }
        } catch (\Exception $ex) {
            return \Config\Services::response()->setJSON(['error' => 'Access denied'])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
