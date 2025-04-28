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
        Schema::create('urun_varyasyonlari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('urun_id');
            $table->string('varyasyon_kodu', 100);
            $table->string('barkod', 50)->nullable();
            $table->integer('stok')->default(0);
            $table->decimal('fiyat', 10, 2);
            $table->json('ozellikler')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['urun_id', 'varyasyon_kodu']);
            $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urun_varyasyonlari');
    }
};
