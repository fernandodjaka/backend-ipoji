<?php

namespace App\Controllers;

use App\Models\KeranjangModel;
use App\Models\ProdukModel; // Perlu mengimport model ProdukModel
use CodeIgniter\API\ResponseTrait;

class KeranjangController extends BaseController
{
    protected $keranjangModel;
    protected $produkModel; // Menyimpan instance dari ProdukModel

    public function __construct()
    {
        $this->keranjangModel = new KeranjangModel();
        $this->produkModel = new ProdukModel(); // Inisialisasi ProdukModel
    }

    public function index()
    {
        $cartItems = $this->keranjangModel->findAll();

        $cartWithProducts = [];
        foreach ($cartItems as $item) {
            $produk = $this->produkModel->find($item['id_produk']);
            if ($produk) {
                $item['nama_produk'] = $produk['nama_produk'];
                $item['gambar_produk'] = $produk['gambar_produk'];
                $item['harga_produk'] = $produk['harga_produk'];
                $cartWithProducts[] = $item;
            }
        }

        return $this->response->setJSON($cartWithProducts);
    }

    // Method untuk mengambil item keranjang berdasarkan id_produk
    public function getCartItemsByProductId($id_produk)
    {
        $cartItems = $this->keranjangModel->getCartItemsByProductId($id_produk);

        $cartWithProducts = [];
        foreach ($cartItems as $item) {
            $produk = $this->produkModel->find($item['id_produk']);
            if ($produk) {
                $item['nama_produk'] = $produk['nama_produk'];
                $item['gambar_produk'] = $produk['gambar_produk'];
                $item['harga_produk'] = $produk['harga_produk'];
                $cartWithProducts[] = $item;
            }
        }

        return $this->response->setJSON($cartWithProducts);
    }
}
