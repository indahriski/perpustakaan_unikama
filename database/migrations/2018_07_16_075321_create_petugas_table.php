<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('petugas', function (Blueprint $table) {
         $table->uuid('id');
         $table->string('nama_petugas')->unique();
         $table->string('alamat_petugas');
         $table->string('tlp_petugas');
         $table->timestamps();
         $table->softDeletes();
         $table->primary('id');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('petugas');
    }
}
