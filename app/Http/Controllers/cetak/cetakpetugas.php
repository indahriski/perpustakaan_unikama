<?php
namespace App\Http\Controllers\cetak;

use App\Domain\Repositories\petugasRepository;
use App\Http\Controllers\Controller;

class cetakpetugas extends Controller
{
    protected $crud;

// PENGATURAN KERTAS
    public $kertas_pjg = 297; // portrait
    public $kertas_lbr = 210; // landscape
    public $kertas_pjg1 = 320; // portrait khusus refrensi

    public $font = 'Arial';
    public $field_font_size = 9;
    public $row_font_size = 8;

    public $butuh = false; // jika perlu fungsi AddPage()
    protected $padding_column = 5;
    protected $default_font_size = 8;
    protected $line = 0;

// MENYAMBUNGKAN TABEL PETUGAS KE CETAKPETUGAS
    public function __construct(
        petugasRepository $petugasRepository)
    {
        $this->petugas = $petugasRepository;
       // $this->middleware('auth');
    }

// MENGATUR HALAMAN JIKA PINDAH KE HALAMAN KE 2
    function repeatColumn($pdf, $id, $orientasi = '', $column = '', $height = 29.7)
    {
        $height_of_cell = $height;
        if ($orientasi == 'P') {
            $page_height = $this->kertas_pjg; // orientasi kertas Potrait
        } else if ($orientasi == 'K') {
            $page_height = $this->kertas_pjg1; // orientasi kertas Portait
        } else if ($orientasi == 'L') {
            $page_height = $this->kertas_lbr; // orientasi kertas Landscape
        }
        $space_bottom = $page_height - $pdf->GetY(); // space bottom on page
        if ($height_of_cell > $space_bottom) {
            $this->$column($pdf);
        }

        $this->line = $space_bottom;
    }

// MEMBUAT JUDUL / FIELD
    function Column($pdf)
    {
        $set = $this->butuh;
        if ($set == true) {
            $pdf->AddPage();
        }

        
// ukuran kolom field
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 10, strtoupper('Cetak data petugas'),0, 0, 'C'); // huruf kapital
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(10);
        $pdf->Ln(10);
        $pdf->Cell(40, 10, 'Nama', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Alamat', 'TLR', 0, 'C');
        $pdf->Cell(30, 10, 'Telepon', 'TLR', 0, 'C');
        $pdf->Cell(40, 10, 'Foto', 1, 0, 'C');
        $pdf->Cell(50, 10, 'Email', 1, 0, 'C');
        $pdf->Ln(10);

// MEMBUAT NOMOR KOLOM
//        $pdf->SetFont($this->font, '', 10);
//        $pdf->Cell(30, 5, '(1)', 1, 0, 'C');
//        $pdf->Cell(40, 5, '(2)', 1, 0, 'C');
//        $pdf->Cell(40, 5, '(3)', 1, 0, 'C');
//        $pdf->Cell(40, 5, '(4)', 1, 0, 'C');
//        $pdf->Cell(40, 5, '(5)', 1, 0, 'C');
//        $pdf->Ln(5);
    }

// MEMANGGIL FUNGSI ROUTER KE CONTROLLER
    public function index()
    {
        $pdf = new PdfClass('P', 'mm', 'A4'); // ukuran kertas
        $pdf->AliasNbPages(); // mengaktifkan nomor halaman

        $pdf->is_header = false;
        $pdf->orientasi = 'P'; // ukuran kertas P / L
        $pdf->AddFont('Arial', '', 'arial.php'); // font tulisan

        //Disable automatic page break
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(10, 10, 10); // margin
        $pdf->SetAutoPageBreak(0, 20);
        $pdf->SetTitle('Cetak Data Petugas'); //title
        $pdf->is_footer = true;
        // $pdf->set_widths = 80;
        // $pdf->set_footer =10;
        $pdf->AddPage(); // membuat halaman baru
        $this->Column($pdf); 

        $petugas = $this->petugas->cetak_petugas(1000); // mengambil di repository
        $pdf->SetAligns(['C', 'C', 'C', 'C', 'JC']); // center, center,center, justify
        $pdf->SetWidths([40, 30, 30, 40, 50]); // ukiran kolom
        $pdf->SetFont('arial', '', 9);
        foreach ($petugas as $row) {
            $this->butuh = true;

            $image_height = 25;
            $image_width = 25;

//get current X and Y
            $start_x = $pdf->GetX();
            $start_y = $pdf->GetY();
            $pdf->Row4([$row->nama_petugas, $row->alamat_petugas,$row->tlp_petugas,
            $pdf->Image('assets/foto_petugas/' . $row->image,$pdf->GetX()+107, $pdf->GetY()+2,$image_width,$image_height)
                ,$row->email]); // 
//            $pdf->MultiCell(48, 3,  $pdf->Image('assets/foto_petugas/' . $row->image,$pdf->GetX(), $pdf->GetY(),$image_width,$image_height, 'jpg'), 0, 1, 'L');

//            $pdf->SetXY($start_x + $image_width + 2, $start_y);
            $this->repeatColumn($pdf, $petugas,'P', 'Column');

        }
        $pdf->Output('cetak-data-petugas' . '.pdf', 'I');
        exit;
    }
} 