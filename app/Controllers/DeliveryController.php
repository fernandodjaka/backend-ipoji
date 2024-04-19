<?php

namespace App\Controllers;

use App\Models\DeliveryModel;
use CodeIgniter\RESTful\ResourceController;

class DeliveryController extends ResourceController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        $deliveryModel = new DeliveryModel();
        $deliveries = $deliveryModel->where('id_user', $userId)->findAll();

        return $this->respond(['status' => 'success', 'data' => $deliveries]);
    }

    public function show($id = null)
    {
        $deliveryModel = new DeliveryModel();
        $delivery = $deliveryModel->find($id);

        if (!$delivery) {
            return $this->failNotFound('Shipment not found');
        }

        return $this->respond(['status' => 'success', 'data' => $delivery]);
    }

    public function create()
    {
        $deliveryModel = new DeliveryModel();

        // Dapatkan nilai 'item' dari permintaan POST
        $items = $this->request->getPost('items');

        // Pastikan $items didefinisikan sebelum menggunakannya
        if (!isset($items)) {
            return $this->respond(['status' => 'error', 'message' => 'Item is not defined']);
        }

        // Dapatkan ID Pengguna dari sesi
        $userId = session()->get('user_id');

        $data = [
            'id_user' => $userId, // Tambahkan ID Pengguna dalam data pengiriman
            'id_order' => $this->request->getPost('id_order'),
            'nama_pembeli' => $this->request->getPost('nama_pembeli'),
            'alamat' => $this->request->getPost('alamat'),
            'nomor_handphone' => $this->request->getPost('nomor_handphone'),
            'items' => $items, // Gunakan langsung tanpa decoding
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
            'status_pemrosesan' => $this->request->getPost('status_pemrosesan'),
            'status_pengiriman' => $this->request->getPost('status_pengiriman'),
            'status_selesai' => $this->request->getPost('status_selesai'),
        ];

        $deliveryModel->insert($data);

        return $this->respondCreated(['status' => 'success', 'message' => 'Shipment created successfully']);
    }

    public function update($id = null)
{
    $deliveryModel = new DeliveryModel();

    // Dapatkan data pengiriman berdasarkan ID
    $delivery = $deliveryModel->find($id);

    // Pastikan pengiriman dengan ID tersebut ditemukan
    if (!$delivery) {
        return $this->failNotFound('Shipment not found');
    }

    // Dapatkan status yang dikirim melalui raw input
    $status_pemrosesan = $this->request->getRawInput('status_pemrosesan');
    $status_pengiriman = $this->request->getRawInput('status_pengiriman');
    $status_selesai = $this->request->getRawInput('status_selesai');

    // Perbarui hanya status yang dikirim melalui raw input
    $data = [];
    if (isset($status_pemrosesan)) {
        $data['status_pemrosesan'] = $status_pemrosesan;
    }
    if (isset($status_pengiriman)) {
        $data['status_pengiriman'] = $status_pengiriman;
    }
    if (isset($status_selesai)) {
        $data['status_selesai'] = $status_selesai;
    }

    $data['status_pembayaran'] = 'success'; // Gantilah dengan nilai default yang diinginkan

    // Lakukan validasi jika diperlukan

    // Perbarui status pengiriman
    $deliveryModel->update($id, $data);

    return $this->respond(['status' => 'success', 'message' => 'Shipment status updated successfully']);
}


    public function delete($id = null)
    {
        $deliveryModel = new DeliveryModel();
        $delivery = $deliveryModel->find($id);

        if (!$delivery) {
            return $this->failNotFound('Shipment not found');
        }

        $deliveryModel->delete($id);

        return $this->respond(['status' => 'success', 'message' => 'Shipment deleted successfully']);
    }
}
