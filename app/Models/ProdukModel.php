<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk'; // Nama tabel database yang sesuai
    protected $primaryKey = 'id_produk';
    protected $returnType = 'array'; // Tipe data yang dihasilkan dalam bentuk array
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nama_produk', 'deskripsi_produk', 'harga_produk', 'gambar_produk', 'berat_produk', 'stok_produk'];
    // Tambahkan fungsi untuk mendapatkan semua produk
    public function getAllProducts()
    {
        return $this->findAll();
    }

    // Tambahkan fungsi untuk mendapatkan detail produk berdasarkan ID
    public function getProductById($id)
    {
        return $this->find($id);
    }
}

