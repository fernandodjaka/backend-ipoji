<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class AddressController extends ResourceController
{
    protected $modelName = 'App\Models\AddressModel';
    protected $format    = 'json';

    public function create()
{
    $data = $this->request->getPost();
    log_message('debug', 'Received data for address creation: ' . print_r($data, true));
    
    // Validasi data
    $validation = \Config\Services::validation();
    $validation->setRules([
        'user_id' => 'required',
        'full_name' => 'required',
        'phone_number' => 'required',
        'province' => 'required',
        'city' => 'required',
        'district' => 'required',
        'subdistrict' => 'required',
        'detailed_address' => 'required',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        log_message('error', 'Validation errors: ' . implode(', ', $validation->getErrors()));
        return $this->failValidationErrors($validation->getErrors());
    }

    log_message('debug', 'Validated data: ' . print_r($data, true));

    if ($this->model->insert($data)) {
        log_message('debug', 'Address successfully saved to the database.');
        return $this->respondCreated(['message' => 'Address successfully added']);
    } else {
        log_message('error', 'Failed to save address to the database: ' . implode(', ', $this->model->errors()));
        return $this->failServerError('Failed to add the address');
    }
}
    
    public function update($id = null)
    {
        $data = $this->request->getRawInput();

        if (!$this->model->find($id)) {
            return $this->failNotFound('Address not found');
        }

        // Validasi data
        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required',
            'phone_number' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'detailed_address' => 'required',
        ]);

        if (!$validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        if ($this->model->update($id, $data)) {
            return $this->respond(['message' => 'Address successfully updated']);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Address not found');
        }

        if ($this->model->delete($id)) {
            return $this->respondDeleted(['message' => 'Address successfully deleted']);
        } else {
            return $this->failServerError('Failed to delete the address');
        }
    }

    public function show($id = null)
    {
        $address = $this->model->where('user_id', $id)->findAll();

        if (empty($address)) {
            return $this->failNotFound('No addresses found for this user');
        }

        return $this->respond($address);
    }
}
