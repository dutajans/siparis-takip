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
        Schema::create('urunler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('firma_id');
            $table->string('urun_kodu', 100);
            $table->string('barkod', 50)->nullable();
            $table->string('urun_adi');
            $table->text('aciklama')->nullable();
            $table->integer('stok')->default(0);
            $table->decimal('fiyat', 10, 2);
            $table->decimal('vergi_orani', 5, 2)->default(18.00);
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['firma_id', 'urun_kodu']);
            $table->index('firma_id');
            $table->foreign('firma_id')->references('id')->on('firmalar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urunler');
    }
};
