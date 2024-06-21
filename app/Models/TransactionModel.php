<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'address_id',
        'total_price',
        'status'
    ];

    protected $validationRules = [
        'status' => 'permit_empty|in_list[diproses,ditolak,pesanan selesai]'
    ];

    protected $validationMessages = [
        'status' => [
            'in_list' => 'Invalid status value'
        ]
    ];

    public function createTransaction($data)
    {
        return $this->insert($data);
    }

    public function getTransactionDetails($transactionId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transaction_details');
        return $builder->where('transaction_id', $transactionId)->get()->getResultArray();
    }

    // protected $validationRules = [
    //     'status' => 'required|in_list[diproses,ditolak,pesanan selesai]'
    // ];

    // protected $validationMessages = [
    //     'status' => [
    //         'required' => 'Status is required',
    //         'in_list' => 'Invalid status value'
    //     ]
    // ];
    public function updateTransactionStatus($transactionId, $status)
    {
        $data = ['status' => $status];
        if ($this->validate($data)) {
            return $this->update($transactionId, $data);
        } else {
            return $this->errors();
        }
    }
   
}

