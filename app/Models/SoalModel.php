<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table = 'soal';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'level', 'kategori_id', 'foto', 'soal', 
        'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 
        'jawaban', 'bobot_nilai', 'is_active'
    ];
    protected $useTimestamps = true;

    public function getSoalWithKategori()
    {
        return $this->select('soal.*, kategori.nama as nama_kategori')
                    ->join('kategori', 'kategori.id = soal.kategori_id')
                    ->findAll();
    }
}
