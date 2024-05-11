<?php

namespace App\Controllers;

use App\Models\KeranjangModel;
use CodeIgniter\API\ResponseTrait;

class KeranjangController extends BaseController
{
    use ResponseTrait;

    // Fungsi untuk mendapatkan data keranjang
    // public function index()
    // {
    //     // Inisialisasi model KeranjangModel
    //     $keranjangModel = new KeranjangModel();

    //     // Mendapatkan data keranjang dari model
    //     $keranjang = $keranjangModel->getAllKeranjang();

    //     // Menyusun respons dengan menggunakan ResponseTrait
    //     return $this->respond($keranjang, 200);
    // }

    public function index($userId)
    {
        // Inisialisasi model KeranjangModel
        $keranjangModel = new KeranjangModel();

        // Mendapatkan data keranjang berdasarkan ID pengguna
        $keranjang = $keranjangModel->getKeranjangByUserId($userId);

        // Menyusun respons dengan menggunakan ResponseTrait
        return $this->respond($keranjang, 200);
    }
}
