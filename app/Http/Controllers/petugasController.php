<?php

namespace App\Http\Controllers;

use App\Domain\Entities\petugas;
use App\Http\Requests\petugas\petugasCreateRequest;
use App\Http\Requests\petugas\petugasEditRequest;
use Illuminate\Http\Request;
use App\Domain\Repositories\petugasRepository;

class petugasController extends Controller
{

    /**
     * @var petugasRepository
     */
    protected $petugas;

    /**
     * petugasController constructor.
     * @param petugasRepository $petugas
     */
    public function __construct(petugasRepository $petugas)
    {
        $this->petugas = $petugas;
    }

    
    public function index(Request $request)
    {
        return $this->petugas->paginate(10, $request->input('page'), $column = ['*'], '', $request->input('search'));
    }

    /**
     * @api {get} api/contacts/id Request Get petugas
     * @apiName Getpetugas
     * @apiGroup petugas
     *
     * @apiSuccess {Varchar} nama_petugas of petugas
     * @apiSuccess {Varchar} alamat_petugas of petugas
     * @apiSuccess {Varchar} tlp_petugas of petugas
     */
    public function show($id)
    {
        return $this->petugas->findById($id);
    }

    /**
     * @api {post} api/contacts/ Request Post petugas
     * @apiName Postpetugas
     * @apiGroup petugas
     *
     *
     * @apiParam {Varchar} nama_petugas of petugas
     * @apiParam {Varchar} alamat_petugas of petugas
     * @apiParam {Varchar} tlp_petugas of petugas
     * @apiSuccess {Number} id id of petugas
     */
    public function store(petugasCreateRequest $request)
    {
        $file1 = $request->file('image');

        $original_name1 = $file1->getClientOriginalName();
        $arr1 = str_replace('-', '', $original_name1);


        $fileName1 = date('dmYhi') . $arr1;
        $destinationPath = public_path() . '/assets/foto_petugas';
        $file1->move($destinationPath, $fileName1);

        return $this->petugas->created($fileName1,$request->all());
    }

    /**
     * @api {put} api/contacts/id Request Update petugas by ID
     * @apiName UpdatepetugasByID
     * @apiGroup petugas
     *
     *
     * @apiParam {Varchar} nama_petugas of petugas
     * @apiParam {Varchar} alamat_petugas of petugas
     * @apiParam {Varchar} tlp_petugas of petugas
     *
     *
     * @apiError EmailHasRegitered The Email must diffrerent.
     */
    public function update(Request $request, $id)
    {
        $cari = petugas::find($id);

        // proses hapus foto
if($request->input('hapus_foto') == null){
        if($request->file('image')!= null){
            \File::delete(public_path() . '/assets/foto_petugas/' . $cari->image);
            $file1 = $request->file('image');

            $original_name1 = $file1->getClientOriginalName();
            $arr1 = str_replace('-', '', $original_name1);


            $fileName1 = date('dmYhi') . $arr1;
            $destinationPath = public_path() . '/assets/foto_petugas';
            $file1->move($destinationPath, $fileName1);
        }
        else {
            $fileName1 = $cari->image;
        }
    }
    else{
        \File::delete(public_path() . '/assets/foto_petugas/' . $cari->image);
        $fileName1 ='';
    }
    // stop
        return $this->petugas->updated($fileName1,$id, $request->all());
    }

    /**
     * @api {delete} api/contacts/id Request Delete petugas by ID
     * @apiName DeletepetugasByID
     * @apiGroup petugas
     *
     * @apiParam {Number} id id of petugas
     *
     *
     * @apiError petugasNotFound The <code>id</code> of the petugas was not found.
     * @apiError NoAccessRight Only authenticated Admins can access the data.
     */
    public function destroy($id)
    {
        return $this->petugas->delete($id);
    }

    // kirim email
    public function kirim_email ($id){
       try{
        $petugas = petugas::find($id);
        \Mail::send('emails/kontak', [

            'id'    => $petugas->id,
            'nama_petugas'     => $petugas->nama_petugas,
            'alamat_petugas'  => $petugas->alamat_petugas,
            'tlp_petugas'  => $petugas->tlp_petugas,
            'image'  => $petugas->image,
            'email'  => $petugas->email,
            
        ], function ($message) use ($petugas) {

            $message->to($petugas->email);

            $message->subject('Info dari OfficePage');

        });
        return response()->json(['deleted' => true], 200);
    }
    catch (\Exception $e) {
        // store errors to log
        \Log::error('class : ' . petugasRepository::class . ' method : kirim_email | ' . $e);

        return $e;
    }
    }

    //kirim sms
    public function kirim_sms($id,\Nexmo\Client $nexmo){
        $petugas = petugas::find($id);
        try{
            $nexmo->send([
            'to' => $petugas->tlp_petugas,
            'from' => '@leggetter',
            'text' => 'SELAMAT BERGABUNG'
        ]);
        return response()->json(['deleted' => true], 200);
        }
        catch (\Exception $e) {
            // store errors to log
            \Log::error('class : ' . petugasRepository::class . ' method : kirim_sms | ' . $e);
    
            return $e;
        }
    }
}