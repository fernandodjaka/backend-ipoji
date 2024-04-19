<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ArtikelModel;

class ArtikelController extends ResourceController
{
    public function index()
    {
        $model = new ArtikelModel();
        $data['artikel'] = $model->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
{
    $model = new ArtikelModel();
    $article = $model->find($id);

    if ($article) {
        return $this->respond($article);
    } else {
        return $this->failNotFound('Article not found.');
    }
}


public function create()
{
    if ($this->request->getMethod() === 'options') {
        // Handle OPTIONS request (tidak perlu validasi atau penyimpanan data)
        return $this->response->setStatusCode(200);
    }

    helper(['form']);
    $rules = [
        'judul_artikel' => 'required',
        'deskripsi_artikel' => 'required',
        'gambar_produk' => 'uploaded[gambar_artikel]|max_size[gambar_artikel,1024]|is_image[gambar_artikel]|mime_in[gambar_artikel,image/jpg,image/jpeg,image/png]',
    ];

    if (!$this->validate($rules)) {
        return $this->response->setStatusCode(400)->setJSON(['error' => $this->validator->getErrors()]);
    }

    $gambar = $this->request->getFile('gambar_artikel');
    $namaGambar = $gambar->getRandomName();
    $gambar->move('gambar', $namaGambar);

    $data = [
        'judul_artikel' => $this->request->getVar('nama_produk'),
        'deskripsi_artikel' => $this->request->getVar('deskripsi_produk'),
        'gambar_produk' => $namaGambar,
 // Ambil nilai kategori dari request
    ];

    $model = new ArtikelModel();
    $model->insert($data);

    $response = [
        'status' => 200,
        'error' => null,
        'messages' => [
            'success' => 'Data Inserted'
        ]
    ];

    return $this->response->setJSON($response);
}



    public function delete($id = null)
    {
        // Membuat instance dari ProdukModel
        $artikelModel = new ArtikelModel();

        // Mencari produk dengan $id yang diberikan
        $artikel = $artikelModel->find($id);

        // Memeriksa apakah produk dengan $id ditemukan
        if ($artikel) {
            // Mencoba menghapus produk dengan $id yang diberikan
            $proses = $artikelModel->delete($id);

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
            // Jika produk dengan $id tidak ditemukan, kembalikan respons 404 Not Found
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
}