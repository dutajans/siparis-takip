<?php

namespace Database\Seeders;

use App\Models\Firma;
use App\Models\Kullanici;
use App\Models\Rol;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Varsayılan firma oluştur
        $firma = Firma::create([
            'firma_adi' => 'Ana Firma',
            'firma_kodu' => 'anafirma',
            'domain' => 'anafirma.com',
            'aktif' => true,
            'email' => 'admin@anafirma.com',
        ]);

        // Varsayılan roller oluştur
        $yoneticiRolu = Rol::create([
            'firma_id' => $firma->id,
            'rol_adi' => 'Yönetici',
            'izinler' => ['*'], // Tüm izinler
        ]);

        $siparisRolu = Rol::create([
            'firma_id' => $firma->id,
            'rol_adi' => 'Sipariş Sorumlusu',
            'izinler' => [
                'siparisler.goruntule',
                'siparisler.duzenle',
                'siparisler.yonet'
            ],
        ]);

        // Varsayılan admin kullanıcısı oluştur
        Kullanici::create([
            'firma_id' => $firma->id,
            'ad' => 'Yunus',
            'soyad' => 'YILDIZ',
            'email' => 'info@dutajans.com',
            'password' => Hash::make('duta12345.6'),
            'rol_id' => $yoneticiRolu->id,
            'aktif' => true,
        ]);
    }
}
