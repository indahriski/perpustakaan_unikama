<?php
namespace App\Http\Controllers\cetak;

use App\Domain\Repositories\petugasRepository;
use App\Http\Controllers\Controller;

class cetakpetugas1  extends Controller
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


// MEMANGGIL FUNGSI ROUTER KE CONTROLLER
    public function index($id)
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
    
        $petugas = $this->petugas->findById($id); // mengambil di repository
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 10, strtoupper('Data Petugas'),0, 0, 'C'); // huruf kapital
        $pdf->Ln(10);
        $pdf->Ln(10);

        $pdf->SetFont('Arial','', 10);
        $pdf->Ln(20); // mengatur tulisan ke bawah
        $pdf->SetX(80); // mengatur data / tulisan
        $pdf->Cell(25, -15, 'Nama Petugas', 5, '', 'L');
        $pdf->Cell(10);
        $pdf->Cell(80, -15, ':     ' . $petugas->nama_petugas, 0, '', 'L');

        $pdf->Ln(6);
        $pdf->SetX(80);
        $pdf->Cell(25, -15, 'Alamat Petugas', 5, '', 'L');
        $pdf->Cell(10);
        $pdf->Cell(80, -15, ':     ' . $petugas->alamat_petugas, 0, '', 'L');

        $pdf->Ln(6);
        $pdf->SetX(80);
        $pdf->Cell(25, -15, 'Telp Petugas', 5, '', 'L');
        $pdf->Cell(10);
        $pdf->Cell(80, -15, ':     ' . $petugas->tlp_petugas, 0, '', 'L');

        $pdf->Ln(6);
        $pdf->SetX(80);
        $pdf->Cell(25, -15, 'Email', 5, '', 'L');
        $pdf->Cell(10);
        $pdf->Cell(80, -15, ':     ' . $petugas->email, 0, '', 'L');

        $pdf->Image('assets/foto_petugas/' . $petugas->image, 20, 30, 40, 40); // wg,hg,uk,uk

        $pdf->Output('cetak-data-petugas' . '.pdf', 'I');
        exit;
    }
} 