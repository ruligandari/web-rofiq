<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\SoalModel;
use App\Models\NilaiModel;

class DashboardController extends BaseController
{
    protected $siswaModel;
    protected $soalModel;
    protected $nilaiModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->soalModel = new SoalModel();
        $this->nilaiModel = new NilaiModel();
    }

    public function index()
    {
        // Get statistics
        $data['total_siswa'] = $this->siswaModel->countAll();
        $data['total_soal'] = $this->soalModel->countAll();
        $data['total_nilai'] = $this->nilaiModel->countAll();
        
        // Get student grades for chart
        $data['student_grades'] = $this->nilaiModel
            ->select('siswa.nama, nilai.total_nilai as nilai')
            ->join('siswa', 'siswa.id = nilai.siswa_id')
            ->orderBy('nilai.total_nilai', 'DESC')
            ->limit(10)
            ->findAll();
        
        return view('admin/dashboard', $data);
    }
}
