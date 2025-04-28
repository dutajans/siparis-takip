<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firma.active');
    }

    public function index()
    {
        if (Gate::denies('yonet-roller')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $roller = Rol::where('firma_id', $firma_id)->get();

        return view('roller.index', compact('roller'));
    }

    public function create()
    {
        if (Gate::denies('yonet-roller')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        // Tüm kullanılabilir izinlerin listesi
        $available_permissions = $this->getAvailablePermissions();

        return view('roller.create', compact('available_permissions'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('yonet-roller')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;

        $validated = $request->validate([
            'rol_adi' => 'required|string|max:255',
            'izinler' => 'nullable|array',
        ]);

        $rol = new Rol();
        $rol->firma_id = $firma_id;
        $rol->rol_adi = $validated['rol_adi'];
        $rol->izinler = $validated['izinler'] ?? [];
        $rol->save();

        return redirect()->route('roller.index')
            ->with('success', 'Rol başarıyla oluşturuldu.');
    }

    public function edit($id)
    {
        if (Gate::denies('yonet-roller')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $rol = Rol::where('firma_id', $firma_id)->findOrFail($id);

        // Tüm kullanılabilir izinlerin listesi
        $available_permissions = $this->getAvailablePermissions();

        return view('roller.edit', compact('rol', 'available_permissions'));
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('yonet-roller')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $rol = Rol::where('firma_id', $firma_id)->findOrFail($id);

        $validated = $request->validate([
            'rol_adi' => 'required|string|max:255',
            'izinler' => 'nullable|array',
        ]);

        $rol->rol_adi = $validated['rol_adi'];
        $rol->izinler = $validated['izinler'] ?? [];
        $rol->save();

        return redirect()->route('roller.index')
            ->with('success', 'Rol başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        if (Gate::denies('yonet-roller')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $rol = Rol::where('firma_id', $firma_id)->findOrFail($id);

        // Rolün kullanıcılar tarafından kullanılıp kullanılmadığını kontrol et
        if ($rol->kullanicilar()->count() > 0) {
            return back()->withErrors(['message' => 'Bu rol bazı kullanıcılar tarafından kullanılıyor, önce kullanıcıları başka rollere atamalısınız.']);
        }

        $rol->delete();

        return redirect()->route('roller.index')
            ->with('success', 'Rol başarıyla silindi.');
    }

    /**
     * Sistemde tanımlı tüm izinlerin listesini döndürür
     */
    private function getAvailablePermissions()
    {
        return [
            'kullanicilar.goruntule' => 'Kullanıcıları Görüntüleme',
            'kullanicilar.ekle' => 'Kullanıcı Ekleme',
            'kullanicilar.duzenle' => 'Kullanıcı Düzenleme',
            'kullanicilar.sil' => 'Kullanıcı Silme',
            'kullanicilar.yonet' => 'Kullanıcı Yönetimi (Tümü)',

            'roller.goruntule' => 'Rolleri Görüntüleme',
            'roller.ekle' => 'Rol Ekleme',
            'roller.duzenle' => 'Rol Düzenleme',
            'roller.sil' => 'Rol Silme',
            'roller.yonet' => 'Rol Yönetimi (Tümü)',

            'urunler.goruntule' => 'Ürünleri Görüntüleme',
            'urunler.ekle' => 'Ürün Ekleme',
            'urunler.duzenle' => 'Ürün Düzenleme',
            'urunler.sil' => 'Ürün Silme',
            'urunler.yonet' => 'Ürün Yönetimi (Tümü)',

            'siparisler.goruntule' => 'Siparişleri Görüntüleme',
            'siparisler.duzenle' => 'Sipariş Durumu Güncelleme',
            'siparisler.yonet' => 'Sipariş Yönetimi (Tümü)',

            'raporlar.goruntule' => 'Raporları Görüntüleme',

            'firma.ayarlar' => 'Firma Ayarlarını Düzenleme',
            'entegrasyon.yonet' => 'Entegrasyon Ayarlarını Yönetme',

            '*' => 'Tüm İzinler (Yönetici)',
        ];
    }
}
