<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\CartModel;
use App\Models\ProdukModel;
use \Firebase\JWT\JWT; // Import library JWT

class CartController extends BaseController
{
    use ResponseTrait;

    private $secretKey = "HS256"; // Ganti dengan secret key Anda

    public function __construct()
    {
        // Load model dan library yang diperlukan
    }

    public function getUserIdFromToken()
    {
        $token = $this->request->getHeader('Authorization');

        if (!$token) {
            return null;
        }

        $token = str_replace('Bearer ', '', $token);

        try {
            $decodedToken = JWT::decode($token, $this->secretKey, ['HS256']);
            return $decodedToken->data->id_user;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function addToCart($id_produk)
    {
        $userId = $this->getUserIdFromToken(); // Mengambil ID user dari token

        if (!$userId) {
            return $this->failUnauthorized('Unauthorized');
        }

        $produkModel = new ProdukModel();
        $product = $produkModel->find($id_produk);

        if (!$product) {
            return $this->failNotFound('Produk tidak ditemukan.');
        }

        $quantity = 1;
        $totalPrice = $quantity * $product['harga_produk'];

        $cartModel = new CartModel();
        $data = [
            'id_produk' => $id_produk,
            'jumlah' => $quantity,
            'harga_total' => $totalPrice,
            'id_user' => $userId,
        ];

        $cartId = $cartModel->insert($data);

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
        $userId = $this->getUserIdFromToken();
        $cartModel = new CartModel();
        $total = $cartModel->calculateTotal($userId);

        return $this->respond(['total' => $total['harga_total']]);
    }
}
