<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'id_addresses';
    protected $allowedFields = [
        'user_id',
        'full_name',
        'phone_number',
        'province',
        'city',
        'district',
        'subdistrict',
        'detailed_address'
    ];

    protected $validationRules = [
        'user_id' => 'required|is_not_unique[user.id]',  // Pastikan 'users' adalah nama tabel pengguna
        'full_name' => 'required|min_length[3]',
        'phone_number' => 'required|numeric',
        'province' => 'required|min_length[3]',
        'city' => 'required|min_length[3]',
        'district' => 'required|min_length[3]',
        'subdistrict' => 'required|min_length[3]',
        'detailed_address' => 'required|min_length[5]'
    ];
    

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'is_not_unique' => 'User ID does not exist'
        ],
        'full_name' => [
            'required' => 'Full name is required',
            'min_length' => 'Full name must be at least 3 characters long'
        ],
        // Add messages for other fields
    ];
}
