<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AddressModel;

class AddressController extends ResourceController
{
    protected $modelName = 'App\Models\AddressModel';
    protected $format    = 'json';

    public function create()
    {
        $model = new AddressModel();
        $data = $this->request->getPost();
    
        if (empty($data['user_id'])) {
            return $this->failValidationErrors('User ID is required');
        }
    
        if ($model->insert($data)) {
            return $this->respondCreated(['message' => 'Address successfully added']);
        } else {
            return $this->failValidationErrors($model->errors());
        }
    }
    

    public function update($id = null)
    {
        $model = new AddressModel();
        $data = $this->request->getRawInput();

        if (!$model->find($id)) {
            return $this->failNotFound('Address not found');
        }

        if ($model->update($id, $data)) {
            return $this->respond(['message' => 'Address successfully updated']);
        } else {
            return $this->failValidationErrors($model->errors());
        }
    }

    public function delete($id = null)
    {
        $model = new AddressModel();
        if (!$model->find($id)) {
            return $this->failNotFound('Address not found');
        }

        $model->delete($id);
        return $this->respondDeleted(['message' => 'Address successfully deleted']);
    }

    public function show($id = null)
    {
        $model = new AddressModel();
        $address = $model->where('user_id', $id)->findAll();

        if (empty($address)) {
            return $this->failNotFound('No addresses found for this user');
        }

        return $this->respond($address);
    }
}
