<?php

namespace App\Http\Controllers;

use App\Models\Firma;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FirmaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('firma.active');
    }

    public function ayarlar()
    {
        if (Gate::denies('firma.ayarlar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma = Auth::user()->firma;

        return view('firma.ayarlar', compact('firma'));
    }

    public function guncelle(Request $request)
    {
        if (Gate::denies('firma.ayarlar')) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $firma = Auth::user()->firma;

        $validated = $request->validate([
            'firma_adi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefon' => 'nullable|string|max:20',
            'adres' => 'nullable|string',
            'vergi_dairesi' => 'nullable|string|max:255',
            'vergi_no' => 'nullable|string|max:50',
        ]);

        $firma->firma_adi = $validated['firma_adi'];
        $firma->email = $validated['email'];
        $firma->telefon = $validated['telefon'];
        $firma->adres = $validated['adres'];
        $firma->vergi_dairesi = $validated['vergi_dairesi'];
        $firma->vergi_no = $validated['vergi_no'];

        if ($request->hasFile('logo')) {
            // Eski logoyu sil
            if ($firma->logo) {
                Storage::delete('public/firma-logolar/' . $firma->logo);
            }

            $logo = $request->file('logo');
            $logoAdi = time() . '_' . $logo->getClientOriginalName();
            $logo->storeAs('public/firma-logolar', $logoAdi);
            $firma->logo = $logoAdi;
        }

        $firma->save();

        return redirect()->route('firma.ayarlar')
            ->with('success', 'Firma bilgileri başarıyla güncellendi.');
    }

    public function paketler()
    {
        $paketler = Paket::all();
        $firma = Auth::user()->firma;

        return view('firma.paketler', compact('paketler', 'firma'));
    }
}
