<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['email', 'password', 'name'];

   
 // Validation
 protected $validationRules      = [];
 protected $validationMessages   = [];
 protected $skipValidation       = false;
 protected $cleanValidationRules = true;

 // Callbacks
 protected $allowCallbacks = true;
 protected $beforeInsert   = [];
 protected $afterInsert    = [];
 protected $beforeUpdate   = [];
 protected $afterUpdate    = [];
 protected $beforeFind     = [];
 protected $afterFind      = [];
 protected $beforeDelete   = [];
 protected $afterDelete    = [];
}