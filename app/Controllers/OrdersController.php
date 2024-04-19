<?php

// app/Controllers/OrderController.php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\DeliveryModel;

use CodeIgniter\RESTful\ResourceController;

class OrdersController extends ResourceController
{
    public function createOrder()
    {
        
        // Handle OPTIONS request
        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        // Validation rules
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'paymentMethod' => 'required',
            'items' => 'required',
            'totalAmount' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        
        // Prepare data for insertion into OrderModel
        $data = [
            'name' => $this->request->getVar('name'),
            'phone' => $this->request->getVar('phone'),
            'address' => $this->request->getVar('address'),
            'paymentMethod' => $this->request->getVar('paymentMethod'),
            'items' => $this->request->getVar('items'),
            'totalAmount' => $this->request->getVar('totalAmount'),
            'status' => 'pending'
        ];

        // Insert data into OrderModel
        $orderModel = new OrderModel();
        $orderId = $orderModel->insert($data);

        // Prepare data for insertion into ShipmentModel
        $deliveryData = [
            'id_order' => $orderId,
            'nama_pembeli' => $this->request->getVar('name'),
            'alamat' => $this->request->getVar('address'),
            'nomor_handphone' => $this->request->getVar('phone'),
            'items' => $this->request->getVar('items'), // Gunakan langsung tanpa decoding
            'status_pembayaran' => false, // Sesuaikan dengan nilai default
            'status_pemrosesan' => false, // Sesuaikan dengan nilai default
            'status_pengiriman' => false, // Sesuaikan dengan nilai default
            'status_selesai' => false, // Sesuaikan dengan nilai default
        ];

        // Insert data into ShipmentModel
        $deliveryModel = new DeliveryModel();
        $deliveryModel->insert($deliveryData);

        // Response for successful order creation
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Order Created'
            ],
            'data' => ['id' => $orderId]
        ];

        return $this->respondCreated($response);
    }



    public function index()
{
    $orderModel = new OrderModel(); // Ganti OrderModel dengan nama model yang sesuai
    $orders = $orderModel->findAll(); // Mengambil data dari model, sesuaikan dengan metode yang sesuai

    $orderCount = count($orders);

    if (!empty($orders)) {
        $response = [
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'total_orders' => $orderCount, // Menambahkan jumlah pesanan ke respons
            'data' => $orders
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No data found',
            'total_orders' => 0, // Menambahkan jumlah pesanan ke respons dengan nilai default 0
            'data' => []
        ];
    }

    return $this->respond($response);
}

public function delete($id = null)
    {
        $orderModel = new OrderModel();

        $order = $orderModel->find($id);

        if ($order) {
            $orderModel->delete($id);

            $response = [
                'status' => 'success',
                'message' => 'Order deleted successfully',
                'data' => $order
            ];

            return $this->respondDeleted($response);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Order not found',
                'data' => []
            ];

            return $this->failNotFound('Order not found');
        }
    }

    public function updateStatus($id = null)
{
    $status = $this->request->getVar('status');

    // Validasi status, pastikan hanya 'accepted' atau 'rejected'
    if (!in_array($status, ['accepted', 'rejected'])) {
        return $this->failValidationError('Invalid status. Use "accepted" or "rejected".');
    }

    $orderModel = new OrderModel();
    $order = $orderModel->find($id);

    if ($order) {
        $orderModel->update($id, ['status' => $status]);

        $response = [
            'status' => 'success',
            'message' => 'Order status updated successfully',
            'data' => ['id' => $id, 'status' => $status]
        ];

        return $this->respond($response);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Order not found',
            'data' => []
        ];

        return $this->failNotFound('Order not found');
    }
}
}
