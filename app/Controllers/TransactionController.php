<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CartModelCoba;
use App\Models\AddressModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransactionController extends ResourceController
{
    protected $format = 'json';

    public function create()
    {
        $cartModel = new CartModelCoba();
        $addressModel = new AddressModel();
        $transactionModel = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        $data = $this->request->getJSON(true);

        $userId = $data['user_id'];
        $addressId = $data['address_id'];
        $selectedCartItems = $data['selected_cart_items']; // List of selected cart item IDs

        // Get address
        $address = $addressModel->find($addressId);

        if (empty($address)) {
            return $this->failNotFound('Address not found');
        }

        // Validate if address belongs to the user
        if ($address['user_id'] != $userId) {
            return $this->fail('Address does not belong to the user');
        }

        // Ensure 'district' key exists
        if (!isset($address['district'])) {
            return $this->fail('Address "district" key not found');
        }

        // Validate if address contains "jiwan" in district
        $isJiwan = strpos(strtolower($address['district']), 'kecamatan jiwan') !== false;

        // Calculate total price
        $totalPrice = 0;
        $totalWeight = 0;
        $cartItems = [];

        foreach ($selectedCartItems as $cartItemId) {
            $cartItem = $cartModel->getCartItemWithProductDetails($cartItemId);

            // Validate if cart item belongs to the user
            if ($cartItem && $cartItem['user_id'] == $userId) {
                $cartItems[] = $cartItem;
                // Ensure 'harga_produk' exists in $cartItem
                if (!isset($cartItem['harga_produk'])) {
                    return $this->fail('Product price is not available for cart item ID: ' . $cartItemId);
                }
                $totalPrice += $cartItem['harga_produk'] * $cartItem['quantity'];
                $totalWeight += $cartItem['berat_produk'] * $cartItem['quantity']; // Assuming berat_produk is in kg
            } else {
                return $this->fail('Cart item ID: ' . $cartItemId . ' does not belong to the user');
            }
        }

        // Determine shipping cost
        if ($isJiwan && $totalWeight > 25) {
            $shippingCost = 0;
        } else {
            $shippingCost = 10000; // Example shipping cost
        }
        $totalPrice += $shippingCost;

        // Create transaction
        $transactionData = [
            'user_id' => $userId,
            'address_id' => $addressId,
            'total_price' => $totalPrice,
            'status' => '' // Initial status
        ];

        $transactionId = $transactionModel->insert($transactionData, true); // Get inserted ID

        if ($transactionId) {
            // Create transaction details
            foreach ($cartItems as $item) {
                $transactionDetailData = [
                    'transaction_id' => $transactionId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['harga_produk']
                ];
                $transactionDetailModel->insert($transactionDetailData);
            }

            // Update product stock (optional)
            foreach ($cartItems as $item) {
                // Assuming you have a ProductModel
                $productModel = new \App\Models\ProdukModel();
                $product = $productModel->find($item['product_id']);
                $newStock = $product['stok_produk'] - $item['quantity'];
                $productModel->update($item['product_id'], ['stok_produk' => $newStock]);
            }

            // Clear the selected items from the cart
            foreach ($selectedCartItems as $cartItemId) {
                $cartModel->delete($cartItemId);
            }

            return $this->respondCreated(['message' => 'Transaction successfully created']);
        } else {
            return $this->fail('Failed to create transaction');
        }
    }


    public function updateStatus($transactionId)
    {
        $transactionModel = new TransactionModel();
        $cartModel = new CartModelCoba();
        $productModel = new \App\Models\ProdukModel();
        $transactionDetailModel = new TransactionDetailModel();
    
        $data = $this->request->getJSON(true);
        $newStatus = $data['status'];
    
        // Find the transaction
        $transaction = $transactionModel->find($transactionId);
    
        if (empty($transaction)) {
            return $this->failNotFound('Transaction not found');
        }
    
        // Validate new status
        if (!in_array($newStatus, ['diproses', 'ditolak', 'pesanan selesai'])) {
            return $this->fail('Invalid status');
        }
    
        // Update the transaction status
        $updated = $transactionModel->update($transactionId, ['status' => $newStatus]);
    
        if (!$updated) {
            return $this->fail('Failed to update transaction status');
        }
        
        if ($newStatus === 'ditolak') {
            // Restore product stock and cart items
            $transactionDetails = $transactionDetailModel->where('transaction_id', $transactionId)->findAll();
            foreach ($transactionDetails as $detail) {
                $product = $productModel->find($detail['product_id']);
                $newStock = $product['stok_produk'] + $detail['quantity'];
                $productModel->update($detail['product_id'], ['stok_produk' => $newStock]);
    
                // Restore the cart item
                $cartData = [
                    'user_id' => $transaction['user_id'],
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity']
                ];
                $cartModel->insert($cartData);
            }
        }
    
        return $this->respond(['message' => 'Transaction status updated successfully']);
    }
    
public function getTransaction($transactionId)
{
    $transactionModel = new TransactionModel();
    $transactionDetailModel = new TransactionDetailModel();

    // Find the transaction
    $transaction = $transactionModel->find($transactionId);

    if (empty($transaction)) {
        return $this->failNotFound('Transaction not found');
    }

    // Get transaction details
    $transactionDetails = $transactionDetailModel->where('transaction_id', $transactionId)->findAll();

    // Include details in the response
    $response = [
        'transaction' => $transaction,
        'details' => $transactionDetails
    ];

    return $this->respond($response);
}
    }
