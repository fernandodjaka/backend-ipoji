<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    protected $table = 'delivery';
    protected $primaryKey = 'id_delivery';
    protected $allowedFields = [
        'id_order',
        'id_user',
        'nama_pembeli',
        'alamat',
        'nomor_handphone',
        'items',
        'status_pembayaran',
        'status_pemrosesan',
        'status_pengiriman',
        'status_selesai'
    ];

    // ...
}
