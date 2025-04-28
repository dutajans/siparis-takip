<x-layout.default>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{ route('kullanicilar.index') }}" class="text-primary hover:underline">Kullanıcılar</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Yeni Kullanıcı</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Yeni Kullanıcı Ekle</h5>
                </div>

                @if($errors->any())
                    <div class="flex items-center p-3.5 rounded text-danger bg-danger-light dark:bg-danger-dark-light mb-5">
                        <span class="ltr:pr-2 rtl:pl-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path opacity="0.5" d="M12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22Z" fill="currentColor"></path>
                                <path d="M12 8.25C12.4142 8.25 12.75 8.58579 12.75 9V13.5C12.75 13.9142 12.4142 14.25 12 14.25C11.5858 14.25 11.25 13.9142 11.25 13.5V9C11.25 8.58579 11.5858 8.25 12 8.25Z" fill="currentColor"></path>
                                <path d="M12 15.75C12.4142 15.75 12.75 16.0858 12.75 16.5C12.75 16.9142 12.4142 17.25 12 17.25C11.5858 17.25 11.25 16.9142 11.25 16.5C11.25 16.0858 11.5858 15.75 12 15.75Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <div>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('kullanicilar.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="ad">Ad</label>
                            <input id="ad" name="ad" type="text" class="form-input" value="{{ old('ad') }}" required />
                        </div>
                        <div>
                            <label for="soyad">Soyad</label>
                            <input id="soyad" name="soyad" type="text" class="form-input" value="{{ old('soyad') }}" required />
                        </div>
                    </div>
                    <div>
                        <label for="email">E-posta</label>
                        <input id="email" name="email" type="email" class="form-input" value="{{ old('email') }}" required />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="password">Şifre</label>
                            <input id="password" name="password" type="password" class="form-input" required />
                        </div>
                        <div>
                            <label for="password_confirmation">Şifre Tekrar</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="rol_id">Rol</label>
                            <select id="rol_id" name="rol_id" class="form-select" required>
                                <option value="">Rol Seçin</option>
                                @foreach($roller as $rol)
                                    <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                        {{ $rol->rol_adi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="telefon">Telefon</label>
                            <input id="telefon" name="telefon" type="text" class="form-input" value="{{ old('telefon') }}" />
                        </div>
                    </div>
                    <div>
                        <label for="resim">Profil Resmi</label>
                        <input id="resim" name="resim" type="file" class="form-input file:py-2 file:px-4 file:border-0 file:font-semibold p-0 file:bg-primary/90 ltr:file:mr-5 rtl:file:ml-5 file:text-white file:hover:bg-primary" accept="image/*" />
                        <span class="text-sm text-white-dark">Desteklenen formatlar: jpg, jpeg, png (Max: 2MB)</span>
                    </div>
                    <div>
                        <label class="inline-flex cursor-pointer">
                            <input type="checkbox" name="aktif" class="form-checkbox" value="1" {{ old('aktif', '1') == '1' ? 'checked' : '' }} />
                            <span class="text-white-dark">Aktif</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary !mt-6">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</x-layout.default>
