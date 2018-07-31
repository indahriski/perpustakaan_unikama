<?php

namespace App\Http\Controllers\Pages;

use App\Http\Requests\petugas\petugasCreateRequest;
use Illuminate\Http\Request;
use App\Domain\Repositories\petugasRepository;
use App\Http\Controllers\Controller;

class petugasController extends Controller
{

    /**
     * @var petugasRepository
     */
    protected $petugas;

    /**
     * BookController constructor.
     * @param petugasRepository $petugas
     */
    public function __construct(petugasRepository $petugas)
    {
        $this->middleware('auth');
        $this->petugas = $petugas;
    }

     public function index(Request $request)
    {
        $petugas = $this->petugas->paginate(10, $request->input('nama_petugas'), $column = ['*'], '', $request->input('search'));
        return view('pages.list-petugas', ['petugas' => $petugas]);
        // return $Petugas;
    }

    public function create()
    {
        return view('pages.create-petugas');
    }

    public function edit($id)
    {
        $petugas = $this->petugas->findById($id);
        return view('pages.edit-petugas',compact('petugas'));
    }
    public function getList()
    {
        return $this->petugas->getList();
    }
}
