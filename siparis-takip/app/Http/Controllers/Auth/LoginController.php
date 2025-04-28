<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kullanici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Kullanıcıyı aktif mi diye kontrol et
        $kullanici = Kullanici::where('email', $request->email)->first();

        if (!$kullanici || !$kullanici->aktif) {
            throw ValidationException::withMessages([
                'email' => ['Bu hesap aktif değil veya kullanıcı bulunamadı.'],
            ]);
        }

        // Firma aktif mi kontrol et
        if (!$kullanici->firma || !$kullanici->firma->aktif) {
            throw ValidationException::withMessages([
                'email' => ['Bu hesaba bağlı firma aktif değil.'],
            ]);
        }

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

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
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
