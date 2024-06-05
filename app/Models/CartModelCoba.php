<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModelCoba extends Model
{
    protected $table = 'cartcoba';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['user_id', 'product_id', 'quantity'];

    public function addToCart($data)
    {
        $existingItem = $this->where('user_id', $data['user_id'])
                             ->where('product_id', $data['product_id'])
                             ->first();

        if ($existingItem) {
            $data['quantity'] += $existingItem['quantity'];
            return $this->update($existingItem['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    public function updateQuantity($id, $quantity)
    {
        return $this->update($id, ['quantity' => $quantity]);
    }

    public function removeItem($id)
    {
        return $this->delete($id);
    }

    public function getCartByUserId($userId)
    {
        return $this->select('cartcoba.*, produk.nama_produk, produk.gambar_produk, produk.harga_produk')
                    ->join('produk', 'produk.id_produk = cartcoba.product_id')
                    ->where('cartcoba.user_id', $userId)
                    ->findAll();
    }
}