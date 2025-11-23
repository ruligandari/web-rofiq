<?php

/**
 * Generate Template Excel for Import
 * 
 * This script creates a template Excel file with headers only
 * for users to fill in their questions.
 * 
 * This will be placed in public/templates/ directory
 */

require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

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

// Style header
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4472C4']
    ]
];
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

// Add example row with instructions
$sheet->setCellValue('A2', 'Contoh: Apa fungsi utama dari piston?');
$sheet->setCellValue('B2', 'Mengatur bahan bakar');
$sheet->setCellValue('C2', 'Mengubah energi panas menjadi gerak');
$sheet->setCellValue('D2', 'Mendinginkan mesin');
$sheet->setCellValue('E2', 'Menyalakan busi');
$sheet->setCellValue('F2', 'B');
$sheet->setCellValue('G2', '10');

// Style example row (light gray)
$exampleStyle = [
    'font' => [
        'italic' => true,
        'color' => ['rgb' => '7F7F7F']
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'F2F2F2']
    ]
];
$sheet->getStyle('A2:G2')->applyFromArray($exampleStyle);

// Auto-size columns
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Add note
$sheet->setCellValue('A4', 'CATATAN:');
$sheet->setCellValue('A5', '- Hapus baris contoh (baris 2) sebelum mengisi data Anda');
$sheet->setCellValue('A6', '- Kolom A-F wajib diisi');
$sheet->setCellValue('A7', '- Kolom G (Bobot Nilai) opsional, default: 10');
$sheet->setCellValue('A8', '- Jawaban harus berupa huruf A, B, C, atau D');

$sheet->getStyle('A4')->getFont()->setBold(true);

// Create directory if not exists
$dir = __DIR__ . '/public/templates';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Save to file
$writer = new Xlsx($spreadsheet);
$filename = $dir . '/template_import_soal.xlsx';
$writer->save($filename);

echo "Template Excel file created successfully!\n";
echo "File location: $filename\n";
