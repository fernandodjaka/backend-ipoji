<?php

// app/Models/OrderModel.php

// app/Models/OrderModel.php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','name', 'phone', 'address', 'paymentMethod', 'items', 'totalAmount', 'created_at'];
    protected $useTimestamps = true;

    // Hapus properti updated_at
    protected $updatedField = '';
}

