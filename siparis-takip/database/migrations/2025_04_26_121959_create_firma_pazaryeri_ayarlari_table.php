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
        Schema::create('firma_pazaryeri_ayarlari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('firma_id');
            $table->unsignedBigInteger('pazaryeri_id');
            $table->string('api_anahtari')->nullable();
            $table->string('api_sifresi')->nullable();
            $table->string('satici_id')->nullable();
            $table->string('magaza_id')->nullable();
            $table->boolean('test_modu')->default(false);
            $table->boolean('entegrasyon_durumu')->default(false);
            $table->json('ayarlar')->nullable();
            $table->timestamps();

            $table->unique(['firma_id', 'pazaryeri_id']);
            $table->foreign('firma_id')->references('id')->on('firmalar')->onDelete('cascade');
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
        Schema::dropIfExists('firma_pazaryeri_ayarlari');
    }
};
