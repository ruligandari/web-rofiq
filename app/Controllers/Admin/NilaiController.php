<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NilaiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class NilaiController extends BaseController
{
    protected $nilaiModel;

    public function __construct()
    {
        $this->nilaiModel = new NilaiModel();
    }

    public function index()
    {
        $data['nilai'] = $this->nilaiModel->getNilaiWithSiswa();
        return view('admin/nilai/index', $data);
    }

    public function export()
    {
        $nilai = $this->nilaiModel->getNilaiWithSiswa();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Total Nilai');
        $sheet->setCellValue('E1', 'Tanggal Tes');

        $column = 2;
        foreach ($nilai as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 1));
            $sheet->setCellValue('B' . $column, $value['nama_siswa']);
            $sheet->setCellValue('C' . $column, $value['username']);
            $sheet->setCellValue('D' . $column, $value['total_nilai']);
            $sheet->setCellValue('E' . $column, $value['created_at']);
            $column++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Nilai_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
