<?php

namespace App\Models;

use CodeIgniter\Model;

class KeranjangModel extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';
    protected $allowedFields = ['id_user', 'id_produk', 'nama_produk', 'harga_produk', 'gambar_produk'];

    public function getKeranjangByUserId($userId)
    {
        return $this->where('id_user', $userId)->findAll();
    }

    public function tambahProdukKeKeranjang($data)
    {
        return $this->insert($data);
    }
}
