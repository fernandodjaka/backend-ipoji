<?php

namespace App\Controllers;

use App\Models\AddressModel;
use CodeIgniter\RESTful\ResourceController;

class AddressController extends ResourceController
{
    protected $modelName = 'App\Models\AddressModel';
    protected $format = 'json';

    public function create()
    {
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'phone_number' => $this->request->getPost('phone_number'),
            'province' => $this->request->getPost('province'),
            'city' => $this->request->getPost('city'),
            'district' => $this->request->getPost('district'),
            'subdistrict' => $this->request->getPost('subdistrict'),
            'detailed_address' => $this->request->getPost('detailed_address'),
        ];

        if ($this->model->insert($data)) {
            return $this->respondCreated(['status' => 'success', 'message' => 'Address saved successfully']);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    public function index()
    {
        $addresses = $this->model->findAll();
        return $this->respond($addresses);
    }

    public function show($id = null)
    {
        $address = $this->model->find($id);
        if ($address) {
            return $this->respond($address);
        } else {
            return $this->failNotFound('Address not found');
        }
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        if ($this->model->update($id, $data)) {
            return $this->respondUpdated(['status' => 'success', 'message' => 'Address updated successfully']);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Address deleted successfully']);
        } else {
            return $this->failNotFound('Address not found');
        }
    }
}
