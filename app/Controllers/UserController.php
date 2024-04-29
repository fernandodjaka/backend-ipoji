<?php

namespace App\Controllers;

use App\Models\UserModel; // Sesuaikan dengan nama model yang sesuai
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class UserController extends ResourceController
{
    protected $format = 'json';

    public function index()
{
    $model = new UserModel(); // Ganti UserModel dengan nama model yang sesuai
    $data = $model->findAll(); // Mengambil data dari model, sesuaikan dengan metode yang sesuai

    $userCount = count($data);

    if (!empty($data)) {
        $response = [
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'total_users' => $userCount, // Menambahkan jumlah pengguna ke respons
            'data' => $data
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No data found',
            'total_users' => 0, // Menambahkan jumlah pengguna ke respons dengan nilai default 0
            'data' => []
        ];
    }

    return $this->respond($response);
}

public function create() {
    if ($this->request->getMethod() === 'options') {
        // Handle OPTIONS request (tidak perlu validasi atau penyimpanan data)
        return $this->response->setStatusCode(200);
    }

    // Memeriksa apakah email sudah ada dalam database
    $model = new UserModel();
    $existingUser = $model->where('email', $this->request->getVar('email'))->first();
    if ($existingUser) {
        return $this->fail('Email sudah digunakan', 409); // 409: Conflict
    }

    helper(['form']);
    $rules = [
        'email' => 'required',
        'password' => 'required',
        'name' => 'required'
    ];

    if (!$this->validate($rules)) {
        return $this->fail($this->validator->getErrors());
    }

    $data = [
        'email' => $this->request->getVar('email'),
        'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        'name' => $this->request->getVar('name')
    ];

    $model->insert($data);

    $response = [
        'status' => 200, // Change status code to 200 (Created)
        'error' => null,
        'messages' => [
            'success' => "Data Inserted"
        ]
    ];

    return $this->respondCreated($response);
}

    public function update($id = null)
{
    // Membuat instance dari UserModel
    $userModel = new UserModel();

    // Mengambil data user berdasarkan ID
    $user = $userModel->find($id);

    // Memeriksa apakah data user ditemukan
    if ($user) {
        // Mengambil data dari request yang dikirim
        $data = [
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            'name' => $this->request->getVar('name'),
        ];

        // Menggunakan metode update dari UserModel dengan kondisi where berdasarkan ID
        $proses = $userModel->update($id, $data);

        // Memeriksa apakah proses update berhasil
        if ($proses) {
            // Jika berhasil, buat respons dengan status 200 dan informasi sukses
            $response = [
                'status' => 200,
                'messages' => 'Data berhasil diubah',
                'data' => $data,
            ];
        } else {
            // Jika gagal, buat respons dengan status 402 dan informasi kegagalan
            $response = [
                'status' => 402,
                'messages' => 'Gagal diubah',
            ];
        }

        // Mengembalikan respons dalam bentuk JSON
        return $this->respond($response);
    }

    // Jika data tidak ditemukan, kirim respons bahwa data tidak ditemukan
    return $this->failNotFound('Data tidak ditemukan');
}
public function deleteUser($id = null)
    {
        // Membuat instance dari UserModel
        $userModel = new UserModel();
    
        // Mencari user dengan $id yang diberikan
        $user = $userModel->find($id);
    
        // Memeriksa apakah user dengan $id ditemukan
        if ($user) {
            // Mencoba menghapus user dengan $id yang diberikan
            $proses = $userModel->delete($id);
    
            // Memeriksa apakah proses penghapusan berhasil
            if ($proses) {
                // Jika berhasil, persiapkan respons sukses
                $response = [
                    'status' => 200,
                    'messages' => 'Data berhasil dihapus',
                ];
            } else {
                // Jika penghapusan gagal, persiapkan respons kegagalan
                $response = [
                    'status' => 402,
                    'messages' => 'Gagal menghapus data',
                ];
            }
            // Mengembalikan respons
            return $this->respond($response);
        } else {
            // Jika user dengan $id tidak ditemukan, kembalikan respons 404 Not Found
            return $this->failNotFound('Data tidak ditemukan');
        }
    }

    public function totalUsers()
    {
        $model = new UserModel();
        $totalUsers = $model->countAll(); // Menghitung total pengguna dari model

        $response = [
            'status' => 'success',
            'message' => 'Total users retrieved successfully',
            'total_users' => $totalUsers,
        ];

        return $this->respond($response);
    }

}
