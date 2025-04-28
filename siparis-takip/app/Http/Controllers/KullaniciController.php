<?php

namespace App\Http\Controllers;

use App\Models\Kullanici;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class KullaniciController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firma.active');
    }

    public function index()
    {
        // Yetki kontrolü
        if (Gate::denies('yonet-kullanicilar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $kullanicilar = Kullanici::where('firma_id', $firma_id)->with('rol')->get();

        return view('kullanicilar.index', compact('kullanicilar'));
    }

    public function create()
    {
        if (Gate::denies('yonet-kullanicilar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $roller = Rol::where('firma_id', $firma_id)->get();

        return view('kullanicilar.create', compact('roller'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('yonet-kullanicilar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;

        $validated = $request->validate([
            'ad' => 'required|string|max:255',
            'soyad' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:kullanicilar',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:roller,id',
            'telefon' => 'nullable|string|max:20',
            'aktif' => 'boolean',
        ]);

        // Rol firmanın mı kontrol et
        $rol = Rol::findOrFail($validated['rol_id']);
        if ($rol->firma_id != $firma_id) {
            return back()->withErrors(['rol_id' => 'Geçersiz rol seçimi.']);
        }

        $kullanici = new Kullanici();
        $kullanici->firma_id = $firma_id;
        $kullanici->ad = $validated['ad'];
        $kullanici->soyad = $validated['soyad'];
        $kullanici->email = $validated['email'];
        $kullanici->password = Hash::make($validated['password']);
        $kullanici->rol_id = $validated['rol_id'];
        $kullanici->telefon = $validated['telefon'] ?? null;
        $kullanici->aktif = $validated['aktif'] ?? true;

        if ($request->hasFile('resim')) {
            $resim = $request->file('resim');
            $resimAdi = time() . '_' . $resim->getClientOriginalName();
            $resim->storeAs('public/kullanici-resimleri', $resimAdi);
            $kullanici->resim = $resimAdi;
        }

        $kullanici->save();

        return redirect()->route('kullanicilar.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }
    public function edit($id)
    {
        if (Gate::denies('yonet-kullanicilar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $kullanici = Kullanici::where('firma_id', $firma_id)->findOrFail($id);
        $roller = Rol::where('firma_id', $firma_id)->get();

        return view('kullanicilar.edit', compact('kullanici', 'roller'));
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('yonet-kullanicilar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $kullanici = Kullanici::where('firma_id', $firma_id)->findOrFail($id);

        $validated = $request->validate([
            'ad' => 'required|string|max:255',
            'soyad' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:kullanicilar,email,'.$kullanici->id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol_id' => 'required|exists:roller,id',
            'telefon' => 'nullable|string|max:20',
            'aktif' => 'boolean',
        ]);

        // Rol firmanın mı kontrol et
        $rol = Rol::findOrFail($validated['rol_id']);
        if ($rol->firma_id != $firma_id) {
            return back()->withErrors(['rol_id' => 'Geçersiz rol seçimi.']);
        }

        $kullanici->ad = $validated['ad'];
        $kullanici->soyad = $validated['soyad'];
        $kullanici->email = $validated['email'];

        if (!empty($validated['password'])) {
            $kullanici->password = Hash::make($validated['password']);
        }

        $kullanici->rol_id = $validated['rol_id'];
        $kullanici->telefon = $validated['telefon'] ?? null;
        $kullanici->aktif = $validated['aktif'] ?? true;

        if ($request->hasFile('resim')) {
            // Eski resmi sil
            if ($kullanici->resim) {
                Storage::delete('public/kullanici-resimleri/' . $kullanici->resim);
            }

            $resim = $request->file('resim');
            $resimAdi = time() . '_' . $resim->getClientOriginalName();
            $resim->storeAs('public/kullanici-resimleri', $resimAdi);
            $kullanici->resim = $resimAdi;
        }

        $kullanici->save();

        return redirect()->route('kullanicilar.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        if (Gate::denies('yonet-kullanicilar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $kullanici = Kullanici::where('firma_id', $firma_id)->findOrFail($id);

        // Kendini silemesin
        if ($kullanici->id === Auth::id()) {
            return back()->withErrors(['message' => 'Kendinizi silemezsiniz.']);
        }

        // Resmi sil
        if ($kullanici->resim) {
            Storage::delete('public/kullanici-resimleri/' . $kullanici->resim);
        }

        $kullanici->delete();

        return redirect()->route('kullanicilar.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }

    // Kullanıcının kendi profil bilgilerini görebileceği ve düzenleyebileceği metodlar
    public function showProfile()
    {
        $kullanici = Auth::user();
        return view('kullanicilar.profile', compact('kullanici'));
    }

    public function updateProfile(Request $request)
    {
        $kullanici = Auth::user();

        $validated = $request->validate([
            'ad' => 'required|string|max:255',
            'soyad' => 'required|string|max:255',
            'telefon' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:kullanicilar,email,'.$kullanici->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Eğer şifre değişikliği istenmişse mevcut şifreyi kontrol et
        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'], $kullanici->password)) {
                return back()->withErrors(['current_password' => 'Mevcut şifreniz yanlış.']);
            }

            $kullanici->password = Hash::make($validated['password']);
        }

        $kullanici->ad = $validated['ad'];
        $kullanici->soyad = $validated['soyad'];
        $kullanici->telefon = $validated['telefon'] ?? null;
        $kullanici->email = $validated['email'];

        if ($request->hasFile('resim')) {
            // Eski resmi sil
            if ($kullanici->resim) {
                Storage::delete('public/kullanici-resimleri/' . $kullanici->resim);
            }

            $resim = $request->file('resim');
            $resimAdi = time() . '_' . $resim->getClientOriginalName();
            $resim->storeAs('public/kullanici-resimleri', $resimAdi);
            $kullanici->resim = $resimAdi;
        }

        $kullanici->save();

        return redirect()->route('profile')
            ->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }
}
