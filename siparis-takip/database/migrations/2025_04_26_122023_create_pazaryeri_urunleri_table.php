<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pazaryeri_urunleri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('firma_id');
            $table->unsignedBigInteger('urun_id')->nullable();
            $table->unsignedBigInteger('varyasyon_id')->nullable();
            $table->unsignedBigInteger('pazaryeri_id');
            $table->string('pazaryeri_urun_id', 100)->nullable();
            $table->string('pazaryeri_stok_kodu', 100)->nullable();
            $table->decimal('pazaryeri_fiyat', 10, 2)->nullable();
            $table->integer('pazaryeri_stok')->nullable();
            $table->string('kategori_id', 100)->nullable();
            $table->enum('durum', ['beklemede', 'aktif', 'pasif', 'hatali'])->default('beklemede');
            $table->text('hata_mesaji')->nullable();
            $table->timestamps();

            $table->foreign('firma_id')->references('id')->on('firmalar')->onDelete('cascade');
            $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('set null');
            $table->foreign('varyasyon_id')->references('id')->on('urun_varyasyonlari')->onDelete('set null');
            $table->foreign('pazaryeri_id')->references('id')->on('pazaryerleri')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pazaryeri_urunleri');
    }
};
