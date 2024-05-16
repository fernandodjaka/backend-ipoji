<?php

namespace App\Models;

use CodeIgniter\Model;

class KeranjangModel extends Model
{
    protected $table = 'keranjang'; // Nama tabel database
    protected $primaryKey = 'id_keranjang'; // Primary key tabel

    protected $allowedFields = ['id_user', 'id_produk','nama_produk', 'harga_produk', 'gambar_produk']; // Kolom yang dapat diisi

    public function tambahItemKeKeranjang($data)
    {
        // Masukkan data ke dalam tabel database
        $this->insert($data);
    }

    public function getItemsWithProductDetails()
    {
        // Melakukan join dengan tabel produk untuk mendapatkan detail produk
        $this->join('produk', 'produk.nama_produk = keranjang.nama_produk');
        // Pilih kolom yang ingin Anda ambil dari tabel keranjang dan produk
        $this->select('keranjang.id, keranjang.id_user, keranjang.nama_produk, keranjang.harga_produk, keranjang.gambar_produk, produk.id_produk, produk.nama_produk as produk_nama, produk.harga_produk as produk_harga, produk.gambar_produk as produk_gambar');
        // Lakukan query dan kembalikan hasilnya
        return $this->findAll();
    }
    

    public function getKeranjangByUserId($userId)
    {
        // Mendapatkan data keranjang berdasarkan ID pengguna
        return $this->where('id_user', $userId)->findAll();
    }
}
