<x-guest-layout>
    <div class="fixed inset-0 z-50 flex bg-[#F5F5F5]">

        {{-- BAGIAN KIRI --}}
        <div class="hidden lg:flex lg:w-[42%] h-full bg-[#0C343D] text-white relative overflow-hidden flex-col justify-between">
            <div class="relative z-10 px-12 pt-12">
                <div class="mb-20">
                    <img src="{{ asset('images/logo-aera-bridge.png') }}" alt="Aera Bridge" class="h-4 w-auto">
                </div>

                <div class="max-w-[380px]">
                    <p class="text-xs uppercase tracking-[1px] text-[#A9D6DD] font-semibold mb-4">Laman Layanan Publik</p>
                    <h1 class="text-[24px] font-bold leading-[1] mb-5">Satu akun untuk semua layanan anda</h1>
                    <p class="text-[15px] leading-relaxed text-[#D9D9D9]">
                        Lengkapi data diri sekali saja untuk mengakses seluruh layanan terintegrasi dengan aman dan cepat.
                    </p>

                    <div class="w-full h-px bg-white/20 my-5"></div>

                    <div class="space-y-4 text-sm text-[#D9D9D9]">
                        <p class="flex gap-2"><span class="text-white font-semibold">1.</span><span>Isi data kependudukan &amp; kontak</span></p>
                        <p class="flex gap-2"><span class="text-white font-semibold">2.</span><span>Buat nama pengguna dan kata sandi</span></p>
                        <p class="flex gap-2"><span class="text-white font-semibold">3.</span><span>Konfirmasi email untuk mulai</span></p>
                    </div>
                </div>
            </div>

            {{-- DEKORASI LINGKARAN --}}
            <div class="absolute -bottom-10 -left-16 w-48 h-48 rounded-full border border-[#76A5AF]/50"></div>

            <div class="absolute -bottom-8 left-10 w-40 h-40 rounded-full border border-[#76A5AF]/50"></div>

            <div class="absolute -bottom-14 -left-4 w-40 h-40 rounded-full bg-[#76A5AF]"></div>
            <div class="absolute -bottom-12 -left-2 w-32 h-32 rounded-full bg-[#45818E]"></div>
        </div>

        {{-- BAGIAN KANAN --}}
        <div class="w-full lg:w-[58%] h-full overflow-y-auto flex items-start justify-center px-6 py-12">
            <div class="w-full max-w-[720px] bg-white rounded-2xl shadow-sm px-10 py-10 my-auto">

                <div class="mb-7">
                    <h2 class="text-[28px] font-bold text-black">Buat Akun Baru</h2>
                    <p class="text-sm text-[#4A4A4A] mt-2">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-[#0C343D] hover:text-[#45818E] underline">Masuk di sini</a>
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf

                    {{-- DATA DIRI --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase text-[#0C343D] tracking-wide mb-4">Data Diri</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                            <div>
                                <x-input-label for="nik" :value="__('NIK (Opsional)')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="nik" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="text" name="nik" :value="old('nik')" autofocus />
                                <x-input-error :messages="$errors->get('nik')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="nama" :value="__('Nama Lengkap')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="nama" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="text" name="nama" :value="old('nama')" required />
                                <x-input-error :messages="$errors->get('nama')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="alamat" :value="__('Alamat')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="alamat" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="text" name="alamat" :value="old('alamat')" />
                                <x-input-error :messages="$errors->get('alamat')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="tanggal_lahir" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" required />
                                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" class="text-sm font-medium text-[#4A4A4A]" />
                                <select id="jenis_kelamin" name="jenis_kelamin" required class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E] bg-white">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="no_hp" :value="__('No. Telp Aktif')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="no_hp" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="text" name="no_hp" :value="old('no_hp')" required />
                                <x-input-error :messages="$errors->get('no_hp')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="pekerjaan" :value="__('Pekerjaan')" class="text-sm font-medium text-[#4A4A4A]" />
                                <select id="pekerjaan" name="pekerjaan" required class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E] bg-white">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Pelajar/Mahasiswa" {{ old('pekerjaan') == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>Pelajar/Mahasiswa</option>
                                    <option value="PNS" {{ old('pekerjaan') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="Karyawan Swasta" {{ old('pekerjaan') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                                    <option value="Wiraswasta" {{ old('pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="Lainnya" {{ old('pekerjaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <x-input-error :messages="$errors->get('pekerjaan')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="penyandang_disabilitas" :value="__('Penyandang Disabilitas?')" class="text-sm font-medium text-[#4A4A4A]" />
                                <select id="penyandang_disabilitas" name="penyandang_disabilitas" required class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E] bg-white">
                                    <option value="">Pilih</option>
                                    <option value="Tidak" {{ old('penyandang_disabilitas') == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                    <option value="Ya" {{ old('penyandang_disabilitas') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                </select>
                                <x-input-error :messages="$errors->get('penyandang_disabilitas')" class="mt-1 text-xs" />
                            </div>

                        </div>
                    </div>

                    {{-- AKUN --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase text-[#0C343D] tracking-wide mb-4">Akun</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                            <div>
                                <x-input-label for="username" :value="__('Username')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="username" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="text" name="username" :value="old('username')" required />
                                <x-input-error :messages="$errors->get('username')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="email" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="password" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-sm font-medium text-[#4A4A4A]" />
                                <x-text-input id="password_confirmation" class="block mt-1.5 w-full h-11 px-3 text-sm rounded-md border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs" />
                            </div>

                        </div>

                        <p class="mt-3 text-xs text-[#4A4A4A] leading-relaxed">
                            Minimal 8 karakter, kombinasi huruf kapital, huruf kecil, angka dan karakter khusus (@$!%*#?&amp;).
                        </p>
                    </div>

                    {{-- TERMS --}}
                    <div class="flex items-start gap-2.5">
                        <input id="terms" type="checkbox" name="terms" required class="mt-0.5 w-4 h-4 rounded border-[#D9D9D9] text-[#45818E] focus:ring-[#45818E]">
                        <label for="terms" class="text-xs leading-relaxed text-[#4A4A4A]">
                            Saya telah membaca dan menyetujui
                            <a href="#" class="font-semibold text-[#0C343D] hover:text-[#45818E]">Syarat dan Ketentuan Layanan</a>
                        </label>
                    </div>

                    {{-- DAFTAR --}}
                    <x-primary-button class="w-full justify-center h-11 text-sm font-bold uppercase tracking-wide rounded-md bg-[#45818E] hover:bg-[#0C343D] focus:bg-[#0C343D] active:bg-[#0C343D]">
                        {{ __('DAFTAR') }}
                    </x-primary-button>

                    {{-- PEMISAH --}}
                    <div class="flex items-center gap-4">
                        <div class="flex-1 h-px bg-[#D9D9D9]"></div>
                        <span class="text-xs text-[#4A4A4A] whitespace-nowrap">atau daftar dengan</span>
                        <div class="flex-1 h-px bg-[#D9D9D9]"></div>
                    </div>

                    {{-- GOOGLE --}}
                    <button type="button" class="w-full h-11 border border-[#D9D9D9] rounded-md bg-white hover:bg-gray-50 flex items-center justify-center gap-2.5 text-sm text-[#4A4A4A]">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#4285F4" d="M21.35 12.27c0-.79-.07-1.55-.22-2.27H12v4.3h5.24a4.48 4.48 0 0 1-1.94 2.94v2.45h3.14c1.84-1.7 2.91-4.2 2.91-7.42z" />
                            <path fill="#34A853" d="M12 21.5c2.63 0 4.84-.87 6.45-2.36l-3.14-2.45c-.87.58-1.98.92-3.31.92-2.54 0-4.69-1.72-5.46-4.03H3.3v2.53A9.74 9.74 0 0 0 12 21.5z" />
                            <path fill="#FBBC05" d="M6.54 13.58A5.85 5.85 0 0 1 6.23 12c0-.55.11-1.08.31-1.58V7.89H3.3A9.5 9.5 0 0 0 2.25 12c0 1.53.37 2.98 1.05 4.11l3.24-2.53z" />
                            <path fill="#EA4335" d="M12 6.39c1.43 0 2.71.49 3.72 1.46l2.79-2.79C16.84 3.48 14.63 2.5 12 2.5a9.74 9.74 0 0 0-8.7 5.39l3.24 2.53C7.31 8.11 9.46 6.39 12 6.39z" />
                        </svg>
                        <span>Google</span>
                    </button>

                </form>
            </div>
        </div>

    </div>
</x-guest-layout>