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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'son_giris_tarihi' => 'datetime',
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
}
