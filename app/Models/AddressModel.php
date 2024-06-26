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
        'detailed_address',
        'primary'
    ];

    public function getAddressesByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function getPrimaryAddress($userId)
    {
        return $this->where('user_id', $userId)->where('primary', 1)->first();
    }

    public function setPrimaryAddress($userId, $addressId)
    {
        $this->db->transStart();

        $this->where('user_id', $userId)->set(['primary' => 0])->update();
        $result = $this->update($addressId, ['primary' => 1]);

        $this->db->transComplete();

        return $result;
    }

    public function createAddress($data)
    {
        $this->db->transStart();

        $this->where('user_id', $data['user_id'])->set(['primary' => 0])->update();
        $data['primary'] = 1;
        $this->insert($data);

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}