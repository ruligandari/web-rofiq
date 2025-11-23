<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'siswa_id', 'attempt', 'nilai_numerik', 
        'nilai_color', 'nilai_greeting', 'nilai_family', 
        'total_nilai'
    ];
    protected $useTimestamps = true;

    public function getNilaiWithSiswa()
    {
        return $this->select('nilai.*, siswa.nama as nama_siswa, siswa.username')
                    ->join('siswa', 'siswa.id = nilai.siswa_id')
                    ->findAll();
    }
}
