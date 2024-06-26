<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Menambahkan header CORS
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Credentials: true");

        // Menangani preflight request
        if ($request->getMethod() === 'options') {
            exit(0); // Menghentikan eksekusi untuk preflight request
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu menambahkan header lagi di sini
    }
}
