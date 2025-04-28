<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Kullanici extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'kullanicilar';

    protected $fillable = [
        'firma_id',
        'ad',
        'soyad',
        'email',
        'password',
        'rol_id',
        'telefon',
        'resim',
        'aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'son_giris_tarihi' => 'datetime',
        'aktif' => 'boolean',
    ];

    public function firma()
    {
        return $this->belongsTo(Firma::class, 'firma_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    // Tam adı döndüren yardımcı metod
    public function getAdSoyadAttribute()
    {
        return "{$this->ad} {$this->soyad}";
    }

    // Laravel Auth ile uyumluluk için getName metodu
    public function getNameAttribute()
    {
        return $this->ad_soyad;
    }
}
