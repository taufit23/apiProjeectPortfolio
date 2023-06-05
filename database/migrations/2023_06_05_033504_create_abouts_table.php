<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_lahir');
            $table->string('alamat_ktp');
            $table->string('alamat_domisili');
            $table->string('agama');
            $table->string('jenis_kelamin');
            $table->string('avatar');
            $table->string('summary_text');
            $table->string('about_text');
            $table->string('cv_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
