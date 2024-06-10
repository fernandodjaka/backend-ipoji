<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'id_addresses';
    protected $allowedFields = [
        'full_name',
        'phone_number',
        'province',
        'city',
        'district',
        'subdistrict',
        'detailed_address'
    ];
}
