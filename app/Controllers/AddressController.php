<?php

namespace App\Controllers;

use App\Models\AddressModel;
use CodeIgniter\RESTful\ResourceController;

class AddressController extends ResourceController
{
    protected $modelName = 'App\Models\AddressModel';
    protected $format    = 'json';

    public function getAddressesByUser($userId)
    {
        $model = new AddressModel();
        $addresses = $model->getAddressesByUser($userId);

        if (!$addresses) {
            return $this->failNotFound('No addresses found for the specified user');
        }

        return $this->respond($addresses);
    }

    public function getPrimaryAddress($userId)
    {
        $model = new AddressModel();
        $address = $model->getPrimaryAddress($userId);

        if (!$address) {
            return $this->failNotFound('No primary address found for the specified user');
        }

        return $this->respond($address);
    }

    public function create()
    {
        $model = new AddressModel();
        $data = [
            'user_id' => $this->request->getVar('user_id'),
            'full_name' => $this->request->getVar('full_name'),
            'phone_number' => $this->request->getVar('phone_number'),
            'province' => $this->request->getVar('province'),
            'city' => $this->request->getVar('city'),
            'district' => $this->request->getVar('district'),
            'subdistrict' => $this->request->getVar('subdistrict'),
            'detailed_address' => $this->request->getVar('detailed_address')
        ];

        if ($model->createAddress($data)) {
            return $this->respondCreated($data, 'Address created successfully');
        }

        return $this->failValidationErrors($model->errors());
    }

    public function update($id = null)
{
    $model = new AddressModel();
    $data = [
        'full_name' => $this->request->getVar('full_name'),
        'phone_number' => $this->request->getVar('phone_number'),
        'province' => $this->request->getVar('province'),
        'city' => $this->request->getVar('city'),
        'district' => $this->request->getVar('district'),
        'subdistrict' => $this->request->getVar('subdistrict'),
        'detailed_address' => $this->request->getVar('detailed_address')
    ];

    if (empty($data)) {
        return $this->failValidationErrors('No data provided for update');
    }

    $address = $model->find($id);
    if (!$address) {
        return $this->failNotFound('Address not found');
    }

    if ($model->update($id, $data)) {
        return $this->respond($data, 200, 'Address updated successfully');
    }

    return $this->fail('Failed to update address');
}

    public function delete($id = null)
    {
        $model = new AddressModel();

        if ($model->find($id)) {
            $model->delete($id);
            return $this->respondDeleted('Address deleted successfully');
        }

        return $this->failNotFound('Address not found');
    }

    public function setPrimary($userId, $addressId)
    {
        $model = new AddressModel();
        if ($model->setPrimaryAddress($userId, $addressId)) {
            return $this->respond(['message' => 'Primary address set successfully']);
        }
        return $this->fail('Failed to set primary address');
    }
}