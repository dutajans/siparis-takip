<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paketler';

    protected $fillable = [
        'paket_adi',
        'fiyat',
        'periyot',
        'pazaryeri_limiti',
        'urun_limiti',
        'kullanici_limiti',
        'ozellikler',
    ];

    protected $casts = [
        'ozellikler' => 'json',
    ];

    public function firmalar()
    {
        return $this->hasMany(Firma::class, 'paket_id');
    }
}
