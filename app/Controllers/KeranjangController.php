<?php

namespace App\Controllers;

use App\Models\KeranjangModel;
use CodeIgniter\API\ResponseTrait;

class KeranjangController extends BaseController
{
    use ResponseTrait;

    // Inisialisasi model KeranjangModel
    protected $keranjangModel;

    public function __construct()
    {
        // Mendefinisikan model KeranjangModel pada konstruktor
        $this->keranjangModel = new KeranjangModel();
    }

    // Fungsi untuk mendapatkan data keranjang berdasarkan user ID
    public function index($userId)
    {
        // Mendapatkan data keranjang berdasarkan ID pengguna
        $keranjang = $this->keranjangModel->getKeranjangByUserId($userId);

        // Menyusun respons dengan menggunakan ResponseTrait
        return $this->respond($keranjang, 200);
    }

    // Fungsi untuk menambah item ke dalam keranjang
    public function tambahItemKeKeranjang()
    {
        // Ambil data yang dikirimkan dari frontend
        $idProduk = $this->request->getPost('id_produk');
        $hargaProduk = $this->request->getPost('harga_produk');
        $userId = $this->request->getPost('id_user');
        $namaProduk = $this->request->getPost('nama_produk');
        $gambarProduk = $this->request->getPost('gambar_produk');
    
        // Validasi data jika diperlukan
    
        // Simpan data ke dalam database menggunakan model
        $data = [
            'id_produk' => $idProduk,
            'harga_produk' => $hargaProduk,
            'id_user' => $userId,
            'nama_produk' => $namaProduk,
            'gambar_produk' => $gambarProduk
        ];
        $this->keranjangModel->tambahItemKeKeranjang($data);
    
        // Berikan respons sesuai keberhasilan penyimpanan
        return $this->respondCreated($data, 'Item berhasil ditambahkan ke keranjang.');
    }    
    
    public function getItemsWithProductDetails()
    {
        // Mendapatkan semua item keranjang dengan detail produknya
        $keranjang = $this->keranjangModel->getItemsWithProductDetails();
    
        // Menyusun respons dengan menggunakan ResponseTrait
        return $this->respond($keranjang, 200);
    }
    
}
