<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SoalModel;
use App\Models\NilaiModel;
use App\Models\SettingModel;
use CodeIgniter\API\ResponseTrait;

class ApiQuizController extends BaseController
{
    use ResponseTrait;

    /**
     * Start Quiz - Generate random questions based on settings
     * 
     * @return Response JSON with array of question IDs
     */
    public function start()
    {
        $soalModel = new SoalModel();
        $settingModel = new SettingModel();
        
        // Get question count from settings (default 10)
        $limit = (int) $settingModel->getValue('quiz_question_count', 10);
        
        // Get all active questions
        $allSoal = $soalModel->where('is_active', 1)->findAll();
        
        if (count($allSoal) < $limit) {
            return $this->fail("Tidak cukup soal aktif. Minimal $limit soal diperlukan.", 400);
        }
        
        // Shuffle and get random questions
        shuffle($allSoal);
        $randomSoal = array_slice($allSoal, 0, $limit);
        
        // Extract only IDs
        $soalIds = array_map(function($soal) {
            return $soal['id'];
        }, $randomSoal);
        
        return $this->respond([
            'status' => 'success',
            'message' => "$limit soal berhasil digenerate",
            'data' => [
                'soal_id' => $soalIds,
                'total_soal' => $limit
            ]
        ], 200);
    }

    /**
     * Submit Quiz - Save student score
     * 
     * @return Response JSON with success message
     */
    public function submit()
    {
        $nilaiModel = new NilaiModel();
        
        // Get request data
        $siswa_id = $this->request->getVar('siswa_id');
        $total_nilai = $this->request->getVar('total_nilai');
        
        // Validation
        if (!$siswa_id) {
            return $this->fail('siswa_id is required', 400);
        }
        
        if ($total_nilai === null || $total_nilai === '') {
            return $this->fail('total_nilai is required', 400);
        }
        
        // Validate total_nilai range (0-100)
        if ($total_nilai < 0 || $total_nilai > 100) {
            return $this->fail('total_nilai must be between 0 and 100', 400);
        }
        
        // Count existing attempts for this student
        $count = $nilaiModel->where('siswa_id', $siswa_id)->countAllResults();
        $attempt = $count + 1;
        
        // Prepare data
        $data = [
            'siswa_id' => $siswa_id,
            'attempt' => $attempt,
            'total_nilai' => $total_nilai,
            'nilai_numerik' => null,
            'nilai_color' => null,
            'nilai_greeting' => null,
            'nilai_family' => null
        ];
        
        // Insert to database
        try {
            $nilaiModel->insert($data);
            $id = $nilaiModel->getInsertID();
            
            return $this->respond([
                'status' => 'success',
                'message' => 'Nilai berhasil disimpan',
                'data' => [
                    'id' => $id,
                    'siswa_id' => $siswa_id,
                    'attempt' => $attempt,
                    'total_nilai' => $total_nilai
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return $this->fail('Gagal menyimpan nilai: ' . $e->getMessage(), 500);
        }
    }
}
