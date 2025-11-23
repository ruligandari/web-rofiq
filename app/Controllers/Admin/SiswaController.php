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
    public function import()
    {
        return view('admin/siswa/import');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = ['Nama Lengkap', 'Username', 'Password'];
        $sheet->fromArray($headers, null, 'A1');
        
        // Style header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ]
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
        
        // Add example row
        $example = [
            'Budi Santoso',
            'budi123',
            'password123'
        ];
        $sheet->fromArray($example, null, 'A2');
        
        // Style example row
        $exampleStyle = [
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '7F7F7F']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2']
            ]
        ];
        $sheet->getStyle('A2:C2')->applyFromArray($exampleStyle);
        
        // Add notes
        $sheet->setCellValue('A4', 'CATATAN:');
        $sheet->setCellValue('A5', '- Hapus baris contoh (baris 2) sebelum mengisi data Anda');
        $sheet->setCellValue('A6', '- Semua kolom wajib diisi');
        $sheet->setCellValue('A7', '- Username harus unik (belum terdaftar)');
        $sheet->setCellValue('A8', '- Password minimal 6 karakter');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generate file
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_siswa.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function processImport()
    {
        $file = $this->request->getFile('excel_file');
        
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan');
        }
        
        $allowedExtensions = ['xls', 'xlsx'];
        $extension = $file->getClientExtension();
        
        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()->with('error', 'Format file harus .xls atau .xlsx');
        }
        
        try {
            // Load spreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            $imported = 0;
            $errors = [];
            
            // Skip header row (index 0), start from row 1
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Skip empty rows
                if (empty($row[0])) {
                    continue;
                }
                
                // Validate required fields
                if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                    $errors[] = "Baris " . ($i + 1) . ": Data tidak lengkap";
                    continue;
                }
                
                // Check if username exists
                if ($this->siswaModel->where('username', $row[1])->first()) {
                    $errors[] = "Baris " . ($i + 1) . ": Username '{$row[1]}' sudah digunakan";
                    continue;
                }
                
                // Insert data
                $data = [
                    'nama' => $row[0],
                    'username' => $row[1],
                    'password' => password_hash($row[2], PASSWORD_DEFAULT),
                ];
                
                if ($this->siswaModel->save($data)) {
                    $imported++;
                } else {
                    $errors[] = "Baris " . ($i + 1) . ": Gagal menyimpan data";
                }
            }
            
            $message = "Berhasil mengimport $imported siswa";
            if (count($errors) > 0) {
                $message .= ". Terdapat " . count($errors) . " error: " . implode(", ", array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " error lainnya";
                }
            }
            
            return redirect()->to('/admin/siswa')->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
