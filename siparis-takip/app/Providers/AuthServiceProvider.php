<?php

namespace App\Providers;

use App\Models\Kullanici;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Tüm izinler için wildcard tanımlama
        Gate::before(function (Kullanici $kullanici, $ability) {
            // Eğer kullanıcının rolünde '*' izni varsa her şeye izin ver
            if (isset($kullanici->rol->izinler) &&
                is_array($kullanici->rol->izinler) &&
                in_array('*', $kullanici->rol->izinler)) {
                return true;
            }

            // Normal izin kontrolü
            if (isset($kullanici->rol->izinler) &&
                is_array($kullanici->rol->izinler) &&
                in_array($ability, $kullanici->rol->izinler)) {
                return true;
            }
        });

        // Spesifik izinleri tanımlayabiliriz
        Gate::define('yonet-kullanicilar', function (Kullanici $kullanici) {
            return $this->izinKontrol($kullanici, 'kullanicilar.yonet');
        });

        Gate::define('yonet-roller', function (Kullanici $kullanici) {
            return $this->izinKontrol($kullanici, 'roller.yonet');
        });

        // ... diğer yetkiler
    }

    private function izinKontrol(Kullanici $kullanici, $izin)
    {
        // Eğer admin rolü varsa ve tüm yetkilere sahipse
        if (isset($kullanici->rol->izinler) &&
            is_array($kullanici->rol->izinler) &&
            in_array('*', $kullanici->rol->izinler)) {
            return true;
        }

        // Belirli bir izin için kontrol
        return isset($kullanici->rol->izinler) &&
               is_array($kullanici->rol->izinler) &&
               in_array($izin, $kullanici->rol->izinler);
    }
}
