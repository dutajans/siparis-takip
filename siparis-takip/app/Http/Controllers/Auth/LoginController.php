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
    // Kullanıcı başarılı giriş yaptıktan sonra yönlendirilecek URL
    protected $redirectTo = '/';

    public function showLoginForm()
    {
        return view('auth.boxed-signin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug için kullanıcıyı kontrol et
        $kullanici = Kullanici::where('email', $request->email)->first();
        Log::info('Giriş denemesi:', [
            'email' => $request->email,
            'kullanici_var_mi' => (bool)$kullanici,
            'ip' => $request->ip()
        ]);

        // Login girişimi
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            Log::info('Giriş başarılı', ['user_id' => Auth::id()]);

            // Başarılı giriş sonrası ana sayfaya yönlendir
            return redirect()->intended($this->redirectTo);
        }

        // Başarısız giriş durumunda
        Log::warning('Giriş başarısız', ['email' => $request->email]);

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
