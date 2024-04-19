<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\CartModel;
use App\Models\ProdukModel;

class CartController extends BaseController
{
    use ResponseTrait;

    public function addToCart($id_produk)
    {
        $produkModel = new ProdukModel();
        $product = $produkModel->find($id_produk);

        if (!$product) {
            return $this->failNotFound('Produk tidak ditemukan.');
        }

        $quantity = 1; // Misalnya, tambahkan satu produk ke keranjang
        $totalPrice = $quantity * $product['harga_produk'];

        $cartModel = new CartModel();
        $data = [
            'id_produk' => $id_produk,
            'jumlah' => $quantity,
            'harga_total' => $totalPrice,
            'id_user' => 1, // Ganti dengan ID pengguna yang sesuai
        ];

        $cartId = $cartModel->insert($data); // Menggunakan metode insert bawaan Model

        return $this->respondCreated(['cart_id' => $cartId], 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function deleteItem($id_cart)
    {
        $cartModel = new CartModel();
        $result = $cartModel->delete($id_cart);

        if ($result) {
            return $this->respondDeleted('Produk berhasil dihapus dari keranjang.');
        } else {
            return $this->fail('Gagal menghapus produk dari keranjang.');
        }
    }

    public function getTotal()
    {
        $userId = 1; // Ganti dengan ID pengguna yang sesuai
        $cartModel = new CartModel();
        $total = $cartModel->calculateTotal($userId);

        return $this->respond(['total' => $total['harga_total']]);
    }
}
