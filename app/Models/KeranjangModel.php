<?php

namespace App\Models;

use CodeIgniter\Model;

class KeranjangModel extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';
    protected $allowedFields = ['id_user', 'id_produk', 'nama_produk', 'harga_produk', 'gambar_produk'];

    public function getCartItemsByProductId($id_produk)
    {
        return $this->where('id_produk', $id_produk)->findAll();
    }


    public function tambahProdukKeKeranjang($data)
    {
        return $this->insert($data);
    }
}
