<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SiswaController extends BaseController
{
    protected $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        $data['siswa'] = $this->siswaModel->findAll();
        return view('admin/siswa/index', $data);
    }

    public function create()
    {
        return view('admin/siswa/create');
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
            'username' => 'required|is_unique[siswa.username]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->siswaModel->save([
            'nama' => $this->request->getVar('nama'),
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['siswa'] = $this->siswaModel->find($id);
        return view('admin/siswa/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required',
            'username' => "required|is_unique[siswa.username,id,$id]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getVar('nama'),
            'username' => $this->request->getVar('username'),
        ];

        if ($this->request->getVar('password')) {
            $data['password'] = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }

        $this->siswaModel->update($id, $data);

        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil diupdate');
    }

    public function delete($id)
    {
        $this->siswaModel->delete($id);
        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil dihapus');
    }

    public function export()
    {
        $siswa = $this->siswaModel->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Username');

        $column = 2;
        foreach ($siswa as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 1));
            $sheet->setCellValue('B' . $column, $value['nama']);
            $sheet->setCellValue('C' . $column, $value['username']);
            $column++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Siswa_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
