<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\NilaiModel;
use CodeIgniter\API\ResponseTrait;

class ApiNilaiController extends BaseController
{
    use ResponseTrait;

    /**
     * Get Nilai - History for AR App
     * 
     * @return Response JSON with student score history
     */
    public function getNilai()
    {
        $model = new NilaiModel();
        $siswa_id = $this->request->getVar('siswa_id');

        if (!$siswa_id) {
            return $this->fail('siswa_id is required', 400);
        }

        $data = $model->where('siswa_id', $siswa_id)
                      ->orderBy('created_at', 'DESC')
                      ->findAll();

        return $this->respond([
            'status' => true,
            'data' => $data
        ], 200);
    }
}
