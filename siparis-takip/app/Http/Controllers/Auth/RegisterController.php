<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Firma;
use App\Models\Kullanici;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        // Yeni firma oluştur
        $firma = Firma::create([
            'firma_adi' => $request->firma_adi,
            'firma_kodu' => $request->firma_kodu,
            'domain' => $request->domain,
            'email' => $request->email,
        ]);

        // Yönetici rolü oluştur
        $rol = Rol::create([
            'firma_id' => $firma->id,
            'rol_adi' => 'Yönetici',
            'izinler' => json_encode(['*']), // Tüm izinler
        ]);

        // İlk kullanıcıyı oluştur
        $kullanici = Kullanici::create([
            'firma_id' => $firma->id,
            'ad' => explode(' ', $request->name)[0],
            'soyad' => count(explode(' ', $request->name)) > 1 ? explode(' ', $request->name)[1] : '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $rol->id,
        ]);

        return redirect('/auth/boxed-signin')
            ->with('success', 'Kayıt başarılı! Giriş yapabilirsiniz.');
    }
}
