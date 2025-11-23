<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\SoalModel;
use CodeIgniter\API\ResponseTrait;

class ApiController extends BaseController
{
    use ResponseTrait;

    public function login()
    {
        $model = new SiswaModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $this->respond([
                    'status' => 'success',
                    'data' => $user
                ], 200);
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid username or password'
                ], 401);
            }
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'Invalid username or password'
            ], 401);
        }
    }

    public function getSoal()
    {
        $model = new SoalModel();
        $nomor = $this->request->getVar('nomor');

        if ($nomor) {
            $data = $model->find($nomor);
            if ($data) {
                // Remove kategori_id from response
                unset($data['kategori_id']);
                return $this->respond($data, 200);
            } else {
                return $this->failNotFound('Soal not found');
            }
        }

        // Return all active questions without category info
        $allData = $model->where('is_active', 1)->findAll();
        
        // Remove kategori_id from all items
        $data = array_map(function($item) {
            unset($item['kategori_id']);
            return $item;
        }, $allData);

        return $this->respond($data, 200);
    }
}
