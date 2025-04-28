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
        Schema::create('firmalar', function (Blueprint $table) {
            $table->id();
            $table->string('firma_adi');
            $table->string('firma_kodu', 100)->unique();
            $table->string('domain')->unique();
            $table->boolean('aktif')->default(true);
            $table->unsignedBigInteger('paket_id')->nullable();
            $table->date('baslangic_tarihi')->nullable();
            $table->date('bitis_tarihi')->nullable();
            $table->string('logo')->nullable();
            $table->string('email');
            $table->string('telefon', 20)->nullable();
            $table->text('adres')->nullable();
            $table->string('vergi_dairesi')->nullable();
            $table->string('vergi_no', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firmalar');
    }
};
