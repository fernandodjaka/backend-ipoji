<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'detail_keranjang';
    protected $primaryKey = 'id_detail';
    protected $allowedFields = ['id_cart', 'id_produk', 'jumlah', 'harga_total'];

    public function addToCart($data)
    {
        $this->insert($data);
        return $this->db->insertID();
    }
}
