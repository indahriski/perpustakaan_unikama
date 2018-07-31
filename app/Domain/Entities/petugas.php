<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Contact
 * @package App\Domain\Entities
 */
class petugas extends Entities
{
    use SoftDeletes;

    /**
     * @var array
     */
    // isi tabel
    protected $fillable = [
        'nama_petugas', 'alamat_petugas', 'tlp_petugas', 'image', 'email',
    ];
 protected $primaryKey = 'id'; 
//  protected $table = ' petugass';
    
    // protected $with=['petugas'];
    
    // public function petugas()
    // {
    //     return $this->belongsTo('App\Domain\Entities\petugas', 'petugas_id');
    // }

}
