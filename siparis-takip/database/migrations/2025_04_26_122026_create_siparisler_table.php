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
        Schema::create('siparisler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('firma_id');
            $table->unsignedBigInteger('pazaryeri_id')->nullable();
            $table->string('siparis_no', 100);
            $table->string('pazaryeri_siparis_no', 100)->nullable();
            $table->unsignedBigInteger('musteri_id')->nullable();
            $table->string('musteri_adi');
            $table->string('musteri_soyadi');
            $table->string('musteri_email')->nullable();
            $table->string('musteri_telefon', 50)->nullable();
            $table->decimal('toplam_tutar', 10, 2);
            $table->string('odeme_yontemi', 100)->nullable();
            $table->enum('siparis_durumu', ['beklemede', 'hazirlaniyor', 'kargoya_verildi', 'tamamlandi', 'iptal'])->default('beklemede');
            $table->text('notlar')->nullable();
            $table->timestamps();

            $table->unique(['firma_id', 'siparis_no']);
            $table->index('firma_id');
            $table->foreign('firma_id')->references('id')->on('firmalar')->onDelete('cascade');
            $table->foreign('pazaryeri_id')->references('id')->on('pazaryerleri')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('siparisler');
    }
};
