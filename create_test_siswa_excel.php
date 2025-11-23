<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header
$sheet->setCellValue('A1', 'Nama Lengkap');
$sheet->setCellValue('B1', 'Username');
$sheet->setCellValue('C1', 'Password');

// Data
$data = [
    ['Siswa Test 1', 'testsiswa1', 'password123'],
    ['Siswa Test 2', 'testsiswa2', 'password123'],
    ['Siswa Test 3', 'testsiswa3', 'password123'],
];

$row = 2;
foreach ($data as $item) {
    $sheet->setCellValue('A' . $row, $item[0]);
    $sheet->setCellValue('B' . $row, $item[1]);
    $sheet->setCellValue('C' . $row, $item[2]);
    $row++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('test_siswa_import.xlsx');
echo "File test_siswa_import.xlsx created successfully.\n";
