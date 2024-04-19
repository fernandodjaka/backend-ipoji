<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin'; // Nama tabel database yang sesuai
    protected $primaryKey = 'id';
    protected $returnType = 'array'; // Tipe data yang dihasilkan dalam bentuk array
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nama', 'email', 'password']; // Kolom yang dapat diisi, id dihilangkan karena itu adalah primary key yang seharusnya tidak dimasukkan secara manual.


}


