<?php

namespace App\Http\Controllers;

use App\Models\FirmaPazaryeriAyarlari;
use App\Models\Pazaryeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PazaryeriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firma.active');
    }

    public function index()
    {
        if (Gate::denies('entegrasyon.yonet')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;

        // Aktif tüm pazaryerlerini al
        $pazaryerleri = Pazaryeri::where('aktif', true)->get();

        // Firma için kayıtlı olan pazaryeri ayarlarını al
        $firmaAyarlari = FirmaPazaryeriAyarlari::where('firma_id', $firma_id)
            ->get()
            ->keyBy('pazaryeri_id');

        return view('pazaryerleri.index', compact('pazaryerleri', 'firmaAyarlari'));
    }

    public function ayarlar($id)
    {
        if (Gate::denies('entegrasyon.yonet')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $pazaryeri = Pazaryeri::where('aktif', true)->findOrFail($id);

        // Firma için pazaryeri ayarları
        $ayarlar = FirmaPazaryeriAyarlari::where('firma_id', $firma_id)
            ->where('pazaryeri_id', $id)
            ->first();

        return view('pazaryerleri.ayarlar', compact('pazaryeri', 'ayarlar'));
    }

    public function kaydet(Request $request, $id)
    {
        if (Gate::denies('entegrasyon.yonet')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $pazaryeri = Pazaryeri::where('aktif', true)->findOrFail($id);

        // Her pazaryeri için validasyon kuralları farklı olabilir
        $validationRules = [
            'api_anahtari' => 'required|string',
            'api_sifresi' => 'nullable|string',
            'satici_id' => 'nullable|string',
            'magaza_id' => 'nullable|string',
            'test_modu' => 'boolean',
        ];

        // Trendyol gibi bazı pazaryerleri için ek kurallar eklenebilir
        if ($pazaryeri->kodu === 'trendyol') {
            $validationRules['satici_id'] = 'required|string';
        }

        $validated = $request->validate($validationRules);

        // Ayarları güncelle veya oluştur
        $ayarlar = FirmaPazaryeriAyarlari::updateOrCreate(
            [
                'firma_id' => $firma_id,
                'pazaryeri_id' => $id,
            ],
            [
                'api_anahtari' => $validated['api_anahtari'],
                'api_sifresi' => $validated['api_sifresi'] ?? null,
                'satici_id' => $validated['satici_id'] ?? null,
                'magaza_id' => $validated['magaza_id'] ?? null,
                'test_modu' => $validated['test_modu'] ?? false,
                'entegrasyon_durumu' => true,
            ]
        );

        // Özel ayarlar (JSON) burada kaydedilebilir
        if ($request->has('ayarlar')) {
            $ozelAyarlar = $request->input('ayarlar');
            $ayarlar->ayarlar = $ozelAyarlar;
            $ayarlar->save();
        }

        return redirect()->route('pazaryerleri.index')
            ->with('success', $pazaryeri->pazaryeri_adi . ' entegrasyonu başarıyla kaydedildi.');
    }

    public function sil($id)
    {
        if (Gate::denies('entegrasyon.yonet')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma_id = Auth::user()->firma_id;
        $pazaryeri = Pazaryeri::findOrFail($id);

        // Entegrasyonu kaldır
        FirmaPazaryeriAyarlari::where('firma_id', $firma_id)
            ->where('pazaryeri_id', $id)
            ->delete();

        return redirect()->route('pazaryerleri.index')
            ->with('success', $pazaryeri->pazaryeri_adi . ' entegrasyonu başarıyla kaldırıldı.');
    }
}
