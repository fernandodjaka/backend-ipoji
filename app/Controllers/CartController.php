<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class CartController extends BaseController
{
    use ResponseTrait;

    // Metode untuk menambahkan produk ke keranjang
    public function add()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->fail('Only POST requests are allowed');
        }
    
        $id_produk = $this->request->getPost('id_produk');
        $jumlah = $this->request->getPost('jumlah');
        $harga_total = $this->request->getPost('harga_total');
        $id_user = $this->request->getPost('id_user');
    
        $cartModel = new \App\Models\CartModel();
        $data = [
            'id_produk' => $id_produk,
            'jumlah' => $jumlah,
            'harga_total' => $harga_total,
            'id_user' => $id_user
        ];
        $cartModel->insert($data);
    
        return $this->respondCreated(['message' => 'Product added to cart successfully']);
    }
}