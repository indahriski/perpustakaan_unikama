<?php

namespace App\Http\Requests\petugas;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class UserCreateRequest
 *
 * @package App\Http\Requests\User
 */
class petugasCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Declaration an attributes
     *
     * @var array
     */
    protected $attrs = [
        'nama_petugas'    => 'Nama Petugas',
        'alamat_petugas'   => 'Alamat Petugas',
        'tlp_petugas' => 'Telp Petugas',
        'image' => 'Upload File',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [ 
            'image'  => 'mimes:png,jpeg,jpg|max:2000',
            'nama_petugas'    => 'required|unique:petugas|max:225',
            'alamat_petugas'   => 'required|max:225',
            'tlp_petugas' => 'required|max:60',
        ];
    }

    /**
     * @param $validator
     *
     * @return mixed
     */
    public function validator($validator)
    {
        return $validator->make($this->all(), $this->container->call([$this, 'rules']), $this->messages(), $this->attrs);
    }

}
