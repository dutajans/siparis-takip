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
        Schema::create('siparis_urunleri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siparis_id');
            $table->unsignedBigInteger('urun_id')->nullable();
            $table->unsignedBigInteger('varyasyon_id')->nullable();
            $table->string('urun_kodu', 100);
            $table->string('urun_adi');
            $table->integer('miktar');
            $table->decimal('birim_fiyat', 10, 2);
            $table->decimal('toplam_fiyat', 10, 2);
            $table->decimal('vergi_orani', 5, 2);
            $table->string('pazaryeri_urun_id', 100)->nullable();
            $table->timestamps();

            $table->foreign('siparis_id')->references('id')->on('siparisler')->onDelete('cascade');
            $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('set null');
            $table->foreign('varyasyon_id')->references('id')->on('urun_varyasyonlari')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siparis_urunleri');
    }
};
