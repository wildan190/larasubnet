<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis
            $table->string('name'); // Nama voucher
            $table->string('voucher_code')->unique(); // Kode voucher, unik
            $table->text('description'); // Deskripsi voucher
            $table->string('size'); // Ukuran voucher, misalnya 10GB
            $table->integer('duration'); // Durasi dalam hari, misalnya 30 untuk 30 hari
            $table->decimal('price', 8, 2); // Harga voucher
            $table->boolean('isSold')->default(false); // Status apakah voucher sudah terjual
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
