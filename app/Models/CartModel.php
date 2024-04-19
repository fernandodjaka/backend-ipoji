<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart'; // Sesuaikan dengan nama tabel keranjang Anda
    protected $primaryKey = 'id_cart';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_produk', 'jumlah', 'harga_total']; // Sesuaikan dengan struktur tabel cart Anda

    // Jika Anda ingin mengaktifkan fitur soft deletes, Anda dapat mengonfigurasi di sini.
    // protected $useSoftDeletes = true;
    // protected $deletedField = 'deleted_at';

    // Fungsi untuk menambahkan produk ke keranjang
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
        return $this->update($cartId, $data);
    }

    public function calculateTotal($userId)
    {
        return $this->selectSum('harga_total')->where('id_user', $userId)->get()->getRowArray();
    }
}
