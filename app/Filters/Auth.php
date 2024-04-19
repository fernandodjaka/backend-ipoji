<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika user belum login
        $session = Services::session();
        if (!$session->get('logged_in')) {
            // Redirect ke halaman login
            return redirect()->to('/login');
        }

        // Dapatkan ID Pengguna dari data pengguna yang sedang login
        $userId = $session->get('user_id');

        // Simpan ID Pengguna ke dalam session
        $session->set('user_id', $userId);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
