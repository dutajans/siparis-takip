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
        Schema::create('paketler', function (Blueprint $table) {
            $table->id();
            $table->string('paket_adi');
            $table->decimal('fiyat', 10, 2);
            $table->enum('periyot', ['aylik', 'yillik'])->default('aylik');
            $table->integer('pazaryeri_limiti')->nullable();
            $table->integer('urun_limiti')->nullable();
            $table->integer('kullanici_limiti')->nullable();
            $table->json('ozellikler')->nullable();
            $table->timestamps();
        });

        // Foreign key constraint for firmalar table
        Schema::table('firmalar', function (Blueprint $table) {
            $table->foreign('paket_id')->references('id')->on('paketler')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('firmalar', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
        });

        Schema::dropIfExists('paketler');
    }
};
