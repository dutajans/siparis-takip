<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roller';

    protected $fillable = [
        'firma_id',
        'rol_adi',
        'izinler',
    ];

    protected $casts = [
        'izinler' => 'json',
    ];

    public function firma()
    {
        return $this->belongsTo(Firma::class, 'firma_id');
    }

    public function kullanicilar()
    {
        return $this->hasMany(Kullanici::class, 'rol_id');
    }
}
