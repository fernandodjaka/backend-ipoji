<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table = 'artikel'; // Nama tabel database yang sesuai
    protected $primaryKey = 'id_artikel';
    protected $returnType = 'array'; // Tipe data yang dihasilkan dalam bentuk array
    protected $useSoftDeletes = false;

    protected $allowedFields = ['judul_artikel', 'deskripsi_artikel','gambar_produk'];
    // Tambahkan fungsi untuk mendapatkan semua produk
    public function getAllArticle()
    {
        return $this->findAll();
    }

    // Tambahkan fungsi untuk mendapatkan detail produk berdasarkan ID
    public function getArticleById($id)
    {
        return $this->find($id);
    }
}

