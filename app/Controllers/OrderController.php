<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class OrderController extends ResourceController
{
    public function create()
    {
        try {
            // Dapatkan data JSON dari request
            $data = $this->request->getJSON(true);

            // Pastikan data yang diperlukan ada
            if (!isset($data['user_id'], $data['address_id'], $data['total_cost'], $data['items'])) {
                return $this->fail('Required data is missing', 400);
            }

            // Mulai transaksi
            $db = \Config\Database::connect();
            $db->transStart();

            // Simpan order
            $orderModel = new OrderModel();
            $orderId = $orderModel->insert([
                'user_id' => $data['user_id'],
                'address_id' => $data['address_id'],
                'total_cost' => $data['total_cost']
            ]);

            // Simpan item-item order
            $orderItemModel = new OrderItemModel();
            foreach ($data['items'] as $item) {
                $orderItemModel->insert([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity']
                ]);
            }

            // Selesaikan transaksi
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Failed to create order', 500);
            }

            return $this->respond(['message' => 'Order created successfully'], 200);
        } catch (\Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
