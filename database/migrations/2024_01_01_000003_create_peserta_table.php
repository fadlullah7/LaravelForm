<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tempatLahir');
            $table->date('tanggalLahir');
            $table->string('agama');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->tinyInteger('jk')->nullable()->comment('0=Pria, 1=Wanita');
            $table->string('hobi')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('provinsi_id')->nullable()->constrained('provinsi')->onDelete('set null');
            $table->foreignId('kabkot_id')->nullable()->constrained('kabkot')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};
