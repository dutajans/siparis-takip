<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    use HasFactory;

    protected $table = 'firmalar';

    protected $fillable = [
        'firma_adi',
        'firma_kodu',
        'domain',
        'aktif',
        'paket_id',
        'baslangic_tarihi',
        'bitis_tarihi',
        'logo',
        'email',
        'telefon',
        'adres',
        'vergi_dairesi',
        'vergi_no',
    ];

    protected $casts = [
        'baslangic_tarihi' => 'date',
        'bitis_tarihi' => 'date',
        'aktif' => 'boolean',
    ];

    public function kullanicilar()
    {
        return $this->hasMany(Kullanici::class, 'firma_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function roller()
    {
        return $this->hasMany(Rol::class, 'firma_id');
    }
}
