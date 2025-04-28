<x-layout.default>

    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Kullanıcılar</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Liste</span>
            </li>
        </ul>
        <div class="pt-5">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Kullanıcı Listesi</h5>
                    <a href="{{ route('kullanicilar.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Yeni Kullanıcı
                    </a>
                </div>

                @if(session('success'))
                    <div class="flex items-center p-3.5 rounded text-success bg-success-light dark:bg-success-dark-light mb-5">
                        <span class="ltr:pr-2 rtl:pl-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path opacity="0.5" d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="currentColor"></path>
                                <path d="M10.5576 15.4709C10.2239 15.4709 9.9006 15.3354 9.66461 15.0994L7.12064 12.5554C6.93661 12.3714 6.93661 12.0698 7.12064 11.8857L7.55661 11.4498C7.74064 11.2657 8.04221 11.2657 8.22624 11.4498L10.0044 13.2279L15.6632 7.56919C15.8473 7.38516 16.1488 7.38516 16.3329 7.56919L16.7688 8.00515C16.9529 8.18918 16.9529 8.49076 16.7688 8.67479L10.5576 14.886C10.4146 15.029 10.2267 15.1 10.0388 15.1C9.94453 15.1 9.7967 15.092 9.65328 15.0285C9.63876 15.0214 9.62443 15.0138 9.6103 15.0057L9.66461 15.0994C9.42862 15.3354 9.1053 15.4709 8.77161 15.4709H10.5576Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="whitespace-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ad Soyad</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Durum</th>
                                <th class="text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kullanicilar as $kullanici)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="flex items-center">
                                            @if($kullanici->resim)
                                                <img src="{{ asset('storage/kullanici-resimleri/'.$kullanici->resim) }}" alt="avatar" class="w-9 h-9 rounded-full ltr:mr-2 rtl:ml-2 object-cover" />
                                            @else
                                                <span class="border border-gray-300 dark:border-gray-800 rounded-full p-2 ltr:mr-2 rtl:ml-2">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                        <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                                        <path opacity="0.5" d="M20 17.5C20 19.9853 20 22 12 22C4 22 4 19.9853 4 17.5C4 15.0147 7.58172 13 12 13C16.4183 13 20 15.0147 20 17.5Z" stroke="currentColor" stroke-width="1.5" />
                                                    </svg>
                                                </span>
                                            @endif
                                            <span class="whitespace-nowrap">{{ $kullanici->ad_soyad }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $kullanici->email }}</td>
                                    <td>
                                        <span class="badge badge-outline-primary">{{ $kullanici->rol->rol_adi }}</span>
                                    </td>
                                    <td>
                                        @if($kullanici->aktif)
                                            <span class="badge badge-outline-success">Aktif</span>
                                        @else
                                            <span class="badge badge-outline-danger">Pasif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('kullanicilar.edit', $kullanici->id) }}" class="btn btn-sm btn-outline-primary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                    <path opacity="0.5" d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                    <path d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z" stroke="currentColor" stroke-width="1.5"></path>
                                                    <path opacity="0.5" d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797" stroke="currentColor" stroke-width="1.5"></path>
                                                </svg>
                                                Düzenle
                                            </a>
                                            <form action="{{ route('kullanicilar.destroy', $kullanici->id) }}" method="POST" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                        <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                        <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                        <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                        <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                                        <path opacity="0.5" d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="currentColor" stroke-width="1.5"></path>
                                                    </svg>
                                                    Sil
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout.default>
