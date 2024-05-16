<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProdukModel;
use App\Models\KeranjangModel;

class ProdukController extends ResourceController
{
    public function index()
    {
        $model = new ProdukModel();
        $data['produk'] = $model->findAll();
        return $this->respond($data);
    }

    public function show($id = null)
{
    $model = new ProdukModel();
    $product = $model->find($id);

    if ($product) {
        return $this->respond($product);
    } else {
        return $this->failNotFound('Product not found.');
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
        'nama_produk' => 'required',
        'deskripsi_produk' => 'required',
        'harga_produk' => 'required',
        'gambar_produk' => 'uploaded[gambar_produk]|max_size[gambar_produk,1024]|is_image[gambar_produk]|mime_in[gambar_produk,image/jpg,image/jpeg,image/png]',
        'berat_produk' => 'required',
        'stok_produk' => 'required',
    ];

    if (!$this->validate($rules)) {
        return $this->response->setStatusCode(400)->setJSON(['error' => $this->validator->getErrors()]);
    }

    $gambar = $this->request->getFile('gambar_produk');
    $namaGambar = $gambar->getRandomName();
    $gambar->move('gambar', $namaGambar);

    $data = [
        'nama_produk' => $this->request->getVar('nama_produk'),
        'deskripsi_produk' => $this->request->getVar('deskripsi_produk'),
        'harga_produk' => $this->request->getVar('harga_produk'),
        'gambar_produk' => $namaGambar,
        'berat_produk' => $this->request->getVar('berat_produk'),
        'stok_produk' => $this->request->getVar('stok_produk'), // Ambil nilai kategori dari request
    ];

    $model = new ProdukModel();
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
        $produkModel = new ProdukModel();

        // Mencari produk dengan $id yang diberikan
        $produk = $produkModel->find($id);

        // Memeriksa apakah produk dengan $id ditemukan
        if ($produk) {
            // Mencoba menghapus produk dengan $id yang diberikan
            $proses = $produkModel->delete($id);

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

    // Fungsi untuk menambahkan produk ke keranjang
    public function tambahKeKeranjang($id = null)
    {
        // Menerima user_id dari data permintaan
        $user_id = $this->request->getVar('user_id');
    
        // Mengambil data produk dari model ProdukModel
        $model = new ProdukModel();
        $produk = $model->find($id);
    
        // Memeriksa apakah produk ditemukan
        if ($produk) {
            // Inisialisasi model KeranjangModel
            $keranjangModel = new KeranjangModel();
    
            // Persiapkan data untuk dimasukkan ke dalam keranjang
            $data = [
                'id_produk' => $id,
                'user_id' => $user_id, // Menyertakan user_id
                'jumlah' => 1 // Jumlah default, bisa disesuaikan sesuai kebutuhan
            ];
    
            // Memasukkan produk ke dalam keranjang menggunakan model KeranjangModel
            $keranjangModel->insert($data);
    
            // Memberikan respons bahwa produk berhasil ditambahkan ke keranjang
            $response = [
                'status' => 200,
                'messages' => 'Produk berhasil ditambahkan ke keranjang.',
                'data' => $produk // Opsi: Mengembalikan data produk yang ditambahkan ke keranjang
            ];
    
            return $this->respond($response);
        } else {
            // Jika produk tidak ditemukan, kembalikan respons error
            return $this->failNotFound('Produk tidak ditemukan.');
        }
    }
}