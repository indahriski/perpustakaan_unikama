<?php

namespace App\Domain\Repositories;

use App\Domain\Contracts\Crudable;
use App\Domain\Contracts\petugasInterface;
use App\Domain\Entities\petugas;


/**
 * Class petugasRepository
 * @package App\Domain\Repositories
 */
class petugasRepository extends AbstractRepository implements Crudable
{

    /**
     * @var petugas
     */
    protected $model;

    /**
     * petugasRepository constructor.
     * @param petugas $petugas
     */

     //menyambungkan model dari entitis
    public function __construct(petugas $petugas)
    {
        $this->model = $petugas;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @param int $limit
     * @param int $page
     * @param array $column
     * @param string $field
     * @param string $search
     * @return \Illuminate\Pagination\Paginator
     */

     
     // tampilan list petugas
    public function paginate($limit = 10, $page = 1, array $column = ['*'], $field, $search = '')
    {
        // query to aql
        $petugas = $this->model
            ->orderBy('created_at', 'desc')
            ->where('nama_petugas', 'like', '%' . $search . '%')
            ->paginate($limit);

        return $petugas;
    }

    // list cetak semua
    public function cetak_petugas($limit = 10)
    {
        // query to aql
        $petugas = $this->model
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return $petugas;
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */

     //create perugas
    public function created($image, array $data)
    {
        // execute sql insert
        return parent::create([
            'nama_petugas'   => e($data['nama_petugas']),
            'alamat_petugas' => e($data['alamat_petugas']),
            'tlp_petugas'    => e($data['tlp_petugas']),
            'image'          => $image,
            'email'          => e($data['email']),
        ]);

    }

    /**
     * @param $id
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */

     //update petugas
    public function updated($fileName1,$id, array $data)
    {
        return parent::update($id, [
            'image'          => $fileName1,
            'nama_petugas'   => $data['nama_petugas'],
            'alamat_petugas' => $data['alamat_petugas'],
            'tlp_petugas'    => $data['tlp_petugas'],
            'email'          => e($data['email']),
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */

     //delete petugas
    public function delete($id)
    {
        $cari = petugas::find($id);
        \File::delete(public_path() . '/assets/foto_petugas/' . $cari->image);

        return parent::delete($id);
    }


    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */

     // show id di update
    public function findById($id, array $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

    public function getList()
    {
        // query to aql
        $petugas = $this->model->all();
        // if data null
        if (null == $petugas) {
            // set response header not found
            return $this->errorNotFound('Data belum tersedia');
        }

        return $petugas;

    }
}