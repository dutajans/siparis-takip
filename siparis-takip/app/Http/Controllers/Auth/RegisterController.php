<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Firma;
use App\Models\Kullanici;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.boxed-signup');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:kullanicilar'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'firma_adi' => ['required', 'string', 'max:255'],
            'firma_kodu' => ['required', 'string', 'max:100', 'unique:firmalar'],
            'domain' => ['required', 'string', 'max:255', 'unique:firmalar'],
        ]);

        try {
            // Yeni firma oluştur
            $firma = Firma::create([
                'firma_adi' => $request->firma_adi,
                'firma_kodu' => $request->firma_kodu,
                'domain' => $request->domain,
                'email' => $request->email,
                'aktif' => true,
            ]);

            // Yönetici rolü oluştur
            $rol = Rol::create([
                'firma_id' => $firma->id,
                'rol_adi' => 'Yönetici',
                'izinler' => ['*'], // Tüm izinler
            ]);

            // Ad ve soyadı bölelim
            $nameParts = explode(' ', $request->name, 2);
            $ad = $nameParts[0];
            $soyad = count($nameParts) > 1 ? $nameParts[1] : '';

            // İlk kullanıcıyı oluştur
            $kullanici = Kullanici::create([
                'firma_id' => $firma->id,
                'ad' => $ad,
                'soyad' => $soyad,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol_id' => $rol->id,
                'aktif' => true,
            ]);

            Log::info('Yeni kayıt oluşturuldu', [
                'kullanici_id' => $kullanici->id,
                'firma_id' => $firma->id,
                'email' => $request->email,
            ]);

            return redirect('/auth/boxed-signin')
                ->with('success', 'Kayıt başarılı! Giriş yapabilirsiniz.');

        } catch (\Exception $e) {
            Log::error('Kayıt oluşturma hatası', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['genel' => 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage()]);
        }
    }
}
