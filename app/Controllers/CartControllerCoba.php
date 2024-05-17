<?php

namespace App\Controllers;

use App\Models\CartModelCoba;
use CodeIgniter\RESTful\ResourceController;

class CartControllerCoba extends ResourceController
{
    protected $modelName = 'App\Models\CartModelCoba';
    protected $format    = 'json';

    public function __construct()
    {
        $this->model = new CartModelCoba();
    }

    public function add()
    {
        $data = $this->request->getJSON(true);

        if ($this->model->addToCart($data)) {
            return $this->respondCreated(['message' => 'Product added to cart']);
        } else {
            return $this->fail('Failed to add product to cart');
        }
    }

    public function updateQuantity($id)
    {
        $quantity = $this->request->getVar('quantity');

        if ($this->model->updateQuantity($id, $quantity)) {
            return $this->respond(['message' => 'Quantity updated']);
        } else {
            return $this->fail('Failed to update quantity');
        }
    }

    public function removeItem($id)
    {
        if ($this->model->removeItem($id)) {
            return $this->respondDeleted(['message' => 'Item removed from cart']);
        } else {
            return $this->fail('Failed to remove item from cart');
        }
    }

    public function getCart($userId)
    {
        $cartItems = $this->model->getCartByUserId($userId);
        return $this->respond($cartItems);
    }
}