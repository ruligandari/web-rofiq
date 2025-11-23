<?php

/**
 * Create Sample Excel File for Testing Import
 * 
 * This script creates a sample Excel file with the correct format
 * for testing the import functionality.
 * 
 * Usage: php create_sample_excel.php
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header row
$sheet->setCellValue('A1', 'Soal');
$sheet->setCellValue('B1', 'Pilihan A');
$sheet->setCellValue('C1', 'Pilihan B');
$sheet->setCellValue('D1', 'Pilihan C');
$sheet->setCellValue('E1', 'Pilihan D');
$sheet->setCellValue('F1', 'Jawaban');
$sheet->setCellValue('G1', 'Bobot Nilai');

// Make header bold
$sheet->getStyle('A1:G1')->getFont()->setBold(true);

// Add sample questions
$sampleQuestions = [
    [
        'Apa fungsi utama dari piston pada mesin?',
        'Mengatur aliran bahan bakar',
        'Mengubah energi panas menjadi gerak',
        'Mendinginkan mesin',
        'Menyalakan busi',
        'B',
        '10'
    ],
    [
        'Berapa jumlah silinder pada mesin V6?',
        '4 silinder',
        '6 silinder',
        '8 silinder',
        '12 silinder',
        'B',
        '10'
    ],
    [
        'Apa fungsi dari radiator pada kendaraan?',
        'Menambah kecepatan',
        'Mendinginkan mesin',
        'Mengatur bahan bakar',
        'Menyalakan mesin',
        'B',
        '15'
    ]
];

// Add data rows
$row = 2;
foreach ($sampleQuestions as $question) {
    $sheet->setCellValue('A' . $row, $question[0]);
    $sheet->setCellValue('B' . $row, $question[1]);
    $sheet->setCellValue('C' . $row, $question[2]);
    $sheet->setCellValue('D' . $row, $question[3]);
    $sheet->setCellValue('E' . $row, $question[4]);
    $sheet->setCellValue('F' . $row, $question[5]);
    $sheet->setCellValue('G' . $row, $question[6]);
    $row++;
}

// Auto-size columns
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Save to file
$writer = new Xlsx($spreadsheet);
$filename = __DIR__ . '/sample_soal_import.xlsx';
$writer->save($filename);

echo "Sample Excel file created successfully!\n";
echo "File location: $filename\n";
echo "\nYou can use this file to test the import functionality.\n";
echo "The file contains 3 sample questions with the correct format.\n";
