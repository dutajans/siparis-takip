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
        Schema::create('adresler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siparis_id');
            $table->enum('tip', ['fatura', 'teslimat']);
            $table->string('ad_soyad');
            $table->string('telefon', 20)->nullable();
            $table->text('adres');
            $table->string('ilce', 100)->nullable();
            $table->string('sehir', 100);
            $table->string('ulke', 100)->default('Türkiye');
            $table->string('posta_kodu', 20)->nullable();
            $table->string('vergi_dairesi')->nullable();
            $table->string('vergi_no', 50)->nullable();
            $table->timestamps();

            $table->foreign('siparis_id')->references('id')->on('siparisler')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adresler');
    }
};
