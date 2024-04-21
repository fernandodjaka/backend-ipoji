<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id_cart';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_produk', 'jumlah', 'harga_total', 'id_user']; // Menambahkan id_user

    public function addToCart($data)
    {
        $this->insert($data);
        return $this->db->insertID();
    }

    public function getCartContents($userId)
    {
        return $this->where('id_user', $userId)->findAll();
    }

    public function removeFromCart($cartId)
    {
        return $this->delete($cartId);
    }

    public function updateCartItem($cartId, $data)
    {
        return $this->update($cartId, $data); // Memperbaiki parameter update
    }

    public function calculateTotal($userId)
    {
        return $this->selectSum('harga_total')->where('id_user', $userId)->get()->getRowArray();
    }

    // Relasi dengan tabel produk
    public function produk()
    {
        return $this->belongsTo('App\Models\ProdukModel', 'id_produk', 'id_produk');
    }
}
