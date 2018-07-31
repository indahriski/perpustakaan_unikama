<?php


use Illuminate\Database\Seeder;

class petugasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        // truncate record
        DB::table('petugas')->truncate();

        $petugas = [
            ['id' => 1, 'nama_petugas' => 'Indahrs', 'alamat_petugas' => 'Tegalsari', 'tlp_petugas' => '083848084810', 'created_at' => \Carbon\Carbon::now()],
        ];

        // insert batch
        DB::table('petugas')->insert($petugas);
    }
}
