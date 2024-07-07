<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'address_id', 'total_cost', 'status'];

    public function getOrder($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getOrdersByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }
}
