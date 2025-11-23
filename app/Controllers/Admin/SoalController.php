<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SoalModel;
use App\Models\KategoriModel;
use App\Models\SettingModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SoalController extends BaseController
{
    protected $soalModel;
    protected $kategoriModel;
    protected $settingModel;

    public function __construct()
    {
        $this->soalModel = new SoalModel();
        $this->kategoriModel = new KategoriModel();
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $data['soal'] = $this->soalModel->getSoalWithKategori();
        $data['quiz_question_count'] = $this->settingModel->getValue('quiz_question_count', 10);
        return view('admin/soal/index', $data);
    }

    public function updateSettings()
    {
        $count = $this->request->getVar('quiz_question_count');
        
        if ($count < 1) {
            return redirect()->back()->with('error', 'Jumlah soal minimal 1');
        }
        
        $this->settingModel->setValue('quiz_question_count', $count, 'Jumlah soal yang ditampilkan di AR Android');
        
        return redirect()->to('/admin/soal')->with('success', 'Pengaturan berhasil disimpan');
    }

    public function create()
    {
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/soal/create', $data);
    }

    public function store()
    {
        $rules = [
            'soal' => 'required',
            'pilihan_a' => 'required',
            'pilihan_b' => 'required',
            'pilihan_c' => 'required',
            'pilihan_d' => 'required',
            'jawaban' => 'required',
            'bobot_nilai' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get default category (first one)
        $kategori = $this->kategoriModel->first();
        $kategori_id = $kategori ? $kategori['id'] : 1;

        $this->soalModel->save([
            'soal' => $this->request->getVar('soal'),
            'pilihan_a' => $this->request->getVar('pilihan_a'),
            'pilihan_b' => $this->request->getVar('pilihan_b'),
            'pilihan_c' => $this->request->getVar('pilihan_c'),
            'pilihan_d' => $this->request->getVar('pilihan_d'),
            'jawaban' => $this->request->getVar('jawaban'),
            'bobot_nilai' => $this->request->getVar('bobot_nilai'),
            'kategori_id' => $kategori_id,
            'level' => 1, // Default level
            'is_active' => 1
        ]);

        return redirect()->to('/admin/soal')->with('success', 'Data soal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['soal'] = $this->soalModel->find($id);
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('admin/soal/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'soal' => 'required',
            'pilihan_a' => 'required',
            'pilihan_b' => 'required',
            'pilihan_c' => 'required',
            'pilihan_d' => 'required',
            'jawaban' => 'required',
            'bobot_nilai' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->soalModel->update($id, [
            'soal' => $this->request->getVar('soal'),
            'pilihan_a' => $this->request->getVar('pilihan_a'),
            'pilihan_b' => $this->request->getVar('pilihan_b'),
            'pilihan_c' => $this->request->getVar('pilihan_c'),
            'pilihan_d' => $this->request->getVar('pilihan_d'),
            'jawaban' => $this->request->getVar('jawaban'),
            'bobot_nilai' => $this->request->getVar('bobot_nilai'),
        ]);

        return redirect()->to('/admin/soal')->with('success', 'Data soal berhasil diupdate');
    }

    public function delete($id)
    {
        $this->soalModel->delete($id);
        return redirect()->to('/admin/soal')->with('success', 'Data soal berhasil dihapus');
    }

    public function import()
    {
        return view('admin/soal/import');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row
        $headers = ['Soal', 'Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D', 'Jawaban', 'Bobot Nilai'];
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
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);
        
        // Add example row
        $example = [
            'Contoh: Apa fungsi utama dari piston?',
            'Mengatur bahan bakar',
            'Mengubah energi panas menjadi gerak',
            'Mendinginkan mesin',
            'Menyalakan busi',
            'B',
            '10'
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
        $sheet->getStyle('A2:G2')->applyFromArray($exampleStyle);
        
        // Add notes
        $sheet->setCellValue('A4', 'CATATAN:');
        $sheet->setCellValue('A5', '- Hapus baris contoh (baris 2) sebelum mengisi data Anda');
        $sheet->setCellValue('A6', '- Kolom A-F wajib diisi');
        $sheet->setCellValue('A7', '- Kolom G (Bobot Nilai) opsional, default: 10');
        $sheet->setCellValue('A8', '- Jawaban harus berupa huruf A, B, C, atau D');
        $sheet->getStyle('A4')->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generate file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_soal.xlsx"');
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
            $spreadsheet = IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Get default kategori
            $kategori = $this->kategoriModel->first();
            $kategori_id = $kategori ? $kategori['id'] : 1;
            
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
                if (empty($row[0]) || empty($row[1]) || empty($row[2]) || 
                    empty($row[3]) || empty($row[4]) || empty($row[5])) {
                    $errors[] = "Baris " . ($i + 1) . ": Data tidak lengkap";
                    continue;
                }
                
                // Validate answer
                $jawaban = strtoupper(trim($row[5]));
                if (!in_array($jawaban, ['A', 'B', 'C', 'D'])) {
                    $errors[] = "Baris " . ($i + 1) . ": Jawaban harus A, B, C, atau D";
                    continue;
                }
                
                // Get bobot_nilai or use default
                $bobot_nilai = !empty($row[6]) ? (int)$row[6] : 10;
                
                // Insert data
                $data = [
                    'soal' => $row[0],
                    'pilihan_a' => $row[1],
                    'pilihan_b' => $row[2],
                    'pilihan_c' => $row[3],
                    'pilihan_d' => $row[4],
                    'jawaban' => $jawaban,
                    'bobot_nilai' => $bobot_nilai,
                    'kategori_id' => $kategori_id,
                    'level' => 1,
                    'is_active' => 1
                ];
                
                if ($this->soalModel->save($data)) {
                    $imported++;
                } else {
                    $errors[] = "Baris " . ($i + 1) . ": Gagal menyimpan data";
                }
            }
            
            $message = "Berhasil mengimport $imported soal";
            if (count($errors) > 0) {
                $message .= ". Terdapat " . count($errors) . " error: " . implode(", ", array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " error lainnya";
                }
            }
            
            return redirect()->to('/admin/soal')->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
