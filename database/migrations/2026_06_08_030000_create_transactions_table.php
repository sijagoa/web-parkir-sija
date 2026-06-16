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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lokasi');
            $table->string('no_tiket', 255);
            $table->string('no_polisi', 15)->nullable();
            $table->unsignedBigInteger('id_jenis');
            $table->datetime('masuk')->nullable();
            $table->datetime('keluar')->nullable();
            $table->integer('perjam_pertama')->default(0);
            $table->integer('perjam_berikutnya')->default(0);
            $table->integer('max_perhari')->default(0);
            $table->integer('total_jam')->default(0);    // total menit
            $table->integer('total_hari')->default(0);   // total hari (jika > 24 jam)
            $table->integer('total_bayar')->default(0);
            $table->timestamps();

            $table->foreign('id_lokasi')->references('id')->on('locations')->onDelete('cascade');
            $table->foreign('id_jenis')->references('id')->on('vehicle_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
