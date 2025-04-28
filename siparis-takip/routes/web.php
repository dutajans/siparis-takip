<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Authentication Routes - Giriş yapmadan erişilebilir sayfalar
Route::get('/auth/boxed-signin', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes - Giriş yapmadan erişilebilir sayfalar
Route::get('/auth/boxed-signup', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/auth/register', [RegisterController::class, 'register']);

// Password Reset Routes - Giriş yapmadan erişilebilir sayfalar
Route::get('/auth/boxed-password-reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/auth/boxed-password-reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/auth/boxed-reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/auth/boxed-reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Auth sayfalarını görüntüleme rotaları (giriş yapmadan erişilebilir)
Route::view('/auth/boxed-lockscreen', 'auth.boxed-lockscreen');
Route::view('/auth/cover-login', 'auth.cover-login');
Route::view('/auth/cover-register', 'auth.cover-register');
Route::view('/auth/cover-lockscreen', 'auth.cover-lockscreen');
Route::view('/auth/cover-password-reset', 'auth.cover-password-reset');

// Giriş yapmış kullanıcıların erişebileceği tüm rotalar
Route::middleware(['auth', 'firma.active'])->group(function () {
    // Ana sayfa ve dashboard
    Route::get('/', function () {
        return view('index');
    });
    Route::view('/analytics', 'analytics');
    Route::view('/finance', 'finance');
    Route::view('/crypto', 'crypto');

    // Apps sayfaları
    Route::view('/apps/chat', 'apps.chat');
    Route::view('/apps/mailbox', 'apps.mailbox');
    Route::view('/apps/todolist', 'apps.todolist');
    Route::view('/apps/notes', 'apps.notes');
    Route::view('/apps/scrumboard', 'apps.scrumboard');
    Route::view('/apps/contacts', 'apps.contacts');
    Route::view('/apps/calendar', 'apps.calendar');

    // Fatura sayfaları
    Route::view('/apps/invoice/list', 'apps.invoice.list');
    Route::view('/apps/invoice/preview', 'apps.invoice.preview');
    Route::view('/apps/invoice/add', 'apps.invoice.add');
    Route::view('/apps/invoice/edit', 'apps.invoice.edit');

    // Bileşenler sayfaları
    Route::view('/components/tabs', 'ui-components.tabs');
    Route::view('/components/accordions', 'ui-components.accordions');
    Route::view('/components/modals', 'ui-components.modals');
    Route::view('/components/cards', 'ui-components.cards');
    Route::view('/components/carousel', 'ui-components.carousel');
    Route::view('/components/countdown', 'ui-components.countdown');
    Route::view('/components/counter', 'ui-components.counter');
    Route::view('/components/sweetalert', 'ui-components.sweetalert');
    Route::view('/components/timeline', 'ui-components.timeline');
    Route::view('/components/notifications', 'ui-components.notifications');
    Route::view('/components/media-object', 'ui-components.media-object');
    Route::view('/components/list-group', 'ui-components.list-group');
    Route::view('/components/pricing-table', 'ui-components.pricing-table');
    Route::view('/components/lightbox', 'ui-components.lightbox');

    // UI Elementleri sayfaları
    Route::view('/elements/alerts', 'elements.alerts');
    Route::view('/elements/avatar', 'elements.avatar');
    Route::view('/elements/badges', 'elements.badges');
    Route::view('/elements/breadcrumbs', 'elements.breadcrumbs');
    Route::view('/elements/buttons', 'elements.buttons');
    Route::view('/elements/buttons-group', 'elements.buttons-group');
    Route::view('/elements/color-library', 'elements.color-library');
    Route::view('/elements/dropdown', 'elements.dropdown');
    Route::view('/elements/infobox', 'elements.infobox');
    Route::view('/elements/jumbotron', 'elements.jumbotron');
    Route::view('/elements/loader', 'elements.loader');
    Route::view('/elements/pagination', 'elements.pagination');
    Route::view('/elements/popovers', 'elements.popovers');
    Route::view('/elements/progress-bar', 'elements.progress-bar');
    Route::view('/elements/search', 'elements.search');
    Route::view('/elements/tooltips', 'elements.tooltips');
    Route::view('/elements/treeview', 'elements.treeview');
    Route::view('/elements/typography', 'elements.typography');

    // Diğer sayfalar
    Route::view('/charts', 'charts');
    Route::view('/widgets', 'widgets');
    Route::view('/font-icons', 'font-icons');
    Route::view('/dragndrop', 'dragndrop');
    Route::view('/tables', 'tables');

    // Data tabloları
    Route::view('/datatables/advanced', 'datatables.advanced');
    Route::view('/datatables/alt-pagination', 'datatables.alt-pagination');
    Route::view('/datatables/basic', 'datatables.basic');
    Route::view('/datatables/checkbox', 'datatables.checkbox');
    Route::view('/datatables/clone-header', 'datatables.clone-header');
    Route::view('/datatables/column-chooser', 'datatables.column-chooser');
    Route::view('/datatables/export', 'datatables.export');
    Route::view('/datatables/multi-column', 'datatables.multi-column');
    Route::view('/datatables/multiple-tables', 'datatables.multiple-tables');
    Route::view('/datatables/order-sorting', 'datatables.order-sorting');
    Route::view('/datatables/range-search', 'datatables.range-search');
    Route::view('/datatables/skin', 'datatables.skin');
    Route::view('/datatables/sticky-header', 'datatables.sticky-header');

    // Form sayfaları
    Route::view('/forms/basic', 'forms.basic');
    Route::view('/forms/input-group', 'forms.input-group');
    Route::view('/forms/layouts', 'forms.layouts');
    Route::view('/forms/validation', 'forms.validation');
    Route::view('/forms/input-mask', 'forms.input-mask');
    Route::view('/forms/select2', 'forms.select2');
    Route::view('/forms/touchspin', 'forms.touchspin');
    Route::view('/forms/checkbox-radio', 'forms.checkbox-radio');
    Route::view('/forms/switches', 'forms.switches');
    Route::view('/forms/wizards', 'forms.wizards');
    Route::view('/forms/file-upload', 'forms.file-upload');
    Route::view('/forms/quill-editor', 'forms.quill-editor');
    Route::view('/forms/markdown-editor', 'forms.markdown-editor');
    Route::view('/forms/date-picker', 'forms.date-picker');
    Route::view('/forms/clipboard', 'forms.clipboard');

    // Kullanıcı sayfaları
    Route::view('/users/profile', 'users.profile');
    Route::view('/users/user-account-settings', 'users.user-account-settings');

    // Diğer sayfa türleri
    Route::view('/pages/knowledge-base', 'pages.knowledge-base');
    Route::view('/pages/contact-us-boxed', 'pages.contact-us-boxed');
    Route::view('/pages/contact-us-cover', 'pages.contact-us-cover');
    Route::view('/pages/faq', 'pages.faq');
    Route::view('/pages/coming-soon-boxed', 'pages.coming-soon-boxed');
    Route::view('/pages/coming-soon-cover', 'pages.coming-soon-cover');
    Route::view('/pages/error404', 'pages.error404');
    Route::view('/pages/error500', 'pages.error500');
    Route::view('/pages/error503', 'pages.error503');
    Route::view('/pages/maintenence', 'pages.maintenence');

    // Profil Yönetimi
    Route::get('/profile', [App\Http\Controllers\KullaniciController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\KullaniciController::class, 'updateProfile'])->name('profile.update');

    // Kullanıcı Yönetimi (Yetki kontrolü ile)
    Route::middleware(['can:yonet-kullanicilar'])->group(function () {
        Route::resource('kullanicilar', App\Http\Controllers\KullaniciController::class);
    });

    // Rol Yönetimi (Yetki kontrolü ile)
    Route::middleware(['can:yonet-roller'])->group(function () {
        Route::resource('roller', App\Http\Controllers\RolController::class);
    });

    // Firma Ayarları (Yetki kontrolü ile)
    Route::middleware(['can:firma.ayarlar'])->group(function () {
        Route::get('/firma/ayarlar', [App\Http\Controllers\FirmaController::class, 'ayarlar'])->name('firma.ayarlar');
        Route::put('/firma/ayarlar', [App\Http\Controllers\FirmaController::class, 'guncelle'])->name('firma.guncelle');
        Route::get('/firma/paketler', [App\Http\Controllers\FirmaController::class, 'paketler'])->name('firma.paketler');
    });

    // Pazaryeri Entegrasyonları (Yetki kontrolü ile)
    Route::middleware(['can:entegrasyon.yonet'])->group(function () {
        Route::get('/pazaryerleri', [App\Http\Controllers\PazaryeriController::class, 'index'])->name('pazaryerleri.index');
        Route::get('/pazaryerleri/{id}/ayarlar', [App\Http\Controllers\PazaryeriController::class, 'ayarlar'])->name('pazaryerleri.ayarlar');
        Route::post('/pazaryerleri/{id}/kaydet', [App\Http\Controllers\PazaryeriController::class, 'kaydet'])->name('pazaryerleri.kaydet');
        Route::delete('/pazaryerleri/{id}/sil', [App\Http\Controllers\PazaryeriController::class, 'sil'])->name('pazaryerleri.sil');
    });
});

// Hata sayfalarına middleware koymuyoruz çünkü bunlar login olmadan da görüntülenebilmeli
Route::view('/pages/error404', 'pages.error404')->name('error404');
Route::view('/pages/error500', 'pages.error500')->name('error500');
Route::view('/pages/error503', 'pages.error503')->name('error503');
Route::view('/pages/maintenence', 'pages.maintenence')->name('maintenance');

// Laravel'in auth rotalarını çalışması için koruyoruz (ama kullanmayacağız)
Auth::routes(['register' => false, 'login' => false, 'reset' => false]);
