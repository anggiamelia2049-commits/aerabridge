
<section>
    <header class="mb-6 border-b border-[#D9D9D9] pb-5">
        <p class="text-sm font-semibold uppercase tracking-wider text-[#45818E]">
            Informasi Akun
        </p>

        <h3 class="mt-1 text-xl font-semibold text-[#0C343D]">
            Informasi Profil
        </h3>

        <p class="mt-2 text-sm text-[#4A4A4A]">
            Perbarui data diri agar informasi akun Anda tetap sesuai.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}"
          class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <x-input-label for="nik" value="NIK"
                    class="font-medium text-[#4A4A4A]" />
                <x-text-input id="nik" name="nik" type="text"
                    class="mt-2 block w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]"
                    :value="old('nik', $user->nik)"
                    autocomplete="off" />
                <x-input-error class="mt-2" :messages="$errors->get('nik')" />
            </div>

            <div>
                <x-input-label for="nama" value="Nama Lengkap"
                    class="font-medium text-[#4A4A4A]" />
                <x-text-input id="nama" name="nama" type="text"
                    class="mt-2 block w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]"
                    :value="old('nama', $user->nama)"
                    required autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
            </div>

            <div>
                <x-input-label for="username" value="Username"
                    class="font-medium text-[#4A4A4A]" />
                <x-text-input id="username" name="username" type="text"
                    class="mt-2 block w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]"
                    :value="old('username', $user->username)"
                    required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>

            <div>
                <x-input-label for="email" value="Email"
                    class="font-medium text-[#4A4A4A]" />
                <x-text-input id="email" name="email" type="email"
                    class="mt-2 block w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]"
                    :value="old('email', $user->email)"
                    required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="no_hp" value="Nomor HP"
                    class="font-medium text-[#4A4A4A]" />
                <x-text-input id="no_hp" name="no_hp" type="tel"
                    class="mt-2 block w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]"
                    :value="old('no_hp', $user->no_hp)"
                    autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
            </div>

            <div>
                <x-input-label for="jenis_kelamin" value="Jenis Kelamin"
                    class="font-medium text-[#4A4A4A]" />
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="mt-2 block w-full rounded-lg border border-[#D9D9D9] bg-white px-3 py-2.5 text-sm text-[#4A4A4A] focus:border-[#45818E] focus:ring-[#45818E]">
                    <option value="">Pilih jenis kelamin</option>
                    <option value="Laki-laki"
                        @selected(old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki')>
                        Laki-laki
                    </option>
                    <option value="Perempuan"
                        @selected(old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan')>
                        Perempuan
                    </option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
            </div>

            <div>
                <x-input-label for="tanggal_lahir" value="Tanggal Lahir"
                    class="font-medium text-[#4A4A4A]" />
                <x-text-input id="tanggal_lahir" name="tanggal_lahir"
                    type="date"
                    class="mt-2 block w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]"
                    :value="old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
            </div>

            <div>
                <x-input-label for="pekerjaan" value="Pekerjaan"
                    class="font-medium text-[#4A4A4A]" />
                <x-text-input id="pekerjaan" name="pekerjaan" type="text"
                    class="mt-2 block w-full rounded-lg border-[#D9D9D9] focus:border-[#45818E] focus:ring-[#45818E]"
                    :value="old('pekerjaan', $user->pekerjaan)" />
                <x-input-error class="mt-2" :messages="$errors->get('pekerjaan')" />
            </div>

            <div>
                <x-input-label for="penyandang_disabilitas"
                    value="Penyandang Disabilitas"
                    class="font-medium text-[#4A4A4A]" />
                <select id="penyandang_disabilitas"
                    name="penyandang_disabilitas"
                    class="mt-2 block w-full rounded-lg border border-[#D9D9D9] bg-white px-3 py-2.5 text-sm text-[#4A4A4A] focus:border-[#45818E] focus:ring-[#45818E]">
                    <option value="">Pilih jawaban</option>
                    <option value="1"
                        @selected((string) old('penyandang_disabilitas', $user->penyandang_disabilitas) === '1')>
                        Ya
                    </option>
                    <option value="0"
                        @selected((string) old('penyandang_disabilitas', $user->penyandang_disabilitas) === '0')>
                        Tidak
                    </option>
                </select>
                <x-input-error class="mt-2"
                    :messages="$errors->get('penyandang_disabilitas')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="alamat" value="Alamat"
                    class="font-medium text-[#4A4A4A]" />
                <textarea id="alamat" name="alamat" rows="3"
                    class="mt-2 block w-full rounded-lg border border-[#D9D9D9] bg-white px-3 py-2.5 text-sm text-[#4A4A4A] focus:border-[#45818E] focus:ring-[#45818E]"
                    placeholder="Masukkan alamat Anda">{{ old('alamat', $user->alamat) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 border-t border-[#D9D9D9] pt-5">
            <button type="submit"
                class="inline-flex items-center rounded-lg bg-[#45818E] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0C343D] focus:outline-none focus:ring-2 focus:ring-[#76A5AF] focus:ring-offset-2">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm font-medium text-green-700">
                    Profil berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
