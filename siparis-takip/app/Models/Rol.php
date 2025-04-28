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
        'izinler' => 'array',
    ];

    public function firma()
    {
        return $this->belongsTo(Firma::class, 'firma_id');
    }

    public function kullanicilar()
    {
        return $this->hasMany(Kullanici::class, 'rol_id');
    }

    // Rol için yetki kontrolü
    public function hasPermission($permission)
    {
        if (empty($this->izinler)) {
            return false;
        }

        // Tüm izinlere sahipse
        if (in_array('*', $this->izinler)) {
            return true;
        }

        return in_array($permission, $this->izinler);
    }
}
