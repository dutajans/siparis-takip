<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kullanici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.boxed-signin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Kullanıcıyı bul ve debug bilgisi ekle
        $kullanici = Kullanici::where('email', $request->email)->first();
        Log::info('Giriş denemesi', [
            'email' => $request->email,
            'kullanici_bulundu' => (bool) $kullanici,
            'kullanici_aktif' => $kullanici ? $kullanici->aktif : null,
            'firma_aktif' => $kullanici && $kullanici->firma ? $kullanici->firma->aktif : null,
        ]);

        if (!$kullanici) {
            throw ValidationException::withMessages([
                'email' => ['Bu e-posta adresiyle kayıtlı kullanıcı bulunamadı.'],
            ]);
        }

        if (!$kullanici->aktif) {
            throw ValidationException::withMessages([
                'email' => ['Bu hesap aktif değil.'],
            ]);
        }

        // Firma aktif mi kontrol et
        if (!$kullanici->firma || !$kullanici->firma->aktif) {
            throw ValidationException::withMessages([
                'email' => ['Bu hesaba bağlı firma aktif değil.'],
            ]);
        }

        // Kimlik doğrulama bilgilerini tam olarak belirt
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->filled('remember'))) {
            // Giriş başarılı
            $kullanici->son_giris_tarihi = now();
            $kullanici->save();

            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Kimlik doğrulama başarısız olduğunda daha fazla bilgi ekle
        Log::warning('Kimlik doğrulama başarısız', [
            'email' => $request->email,
        ]);

        throw ValidationException::withMessages([
            'email' => ['Girilen bilgiler kayıtlarımızla eşleşmiyor.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth/boxed-signin');
    }
}
