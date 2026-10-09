<x-guest-layout>
    <div class="px-4 sm:px-8 pb-2">
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="AERA Bridge" class="h-100 w-auto">
        </div>

        <h2 class="font-inter font-extrabold text-3xl text-[#0C343D] text-center mb-8">MASUK</h2>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block font-inter font-bold text-sm text-[#0C343D] mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    required autofocus autocomplete="username"
                    class="block w-full h-12 px-4 rounded-xl border border-black bg-transparent text-sm
                           focus:border-[#45818E] focus:ring-1 focus:ring-[#45818E]">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="mb-8">
                <label for="password" class="block font-inter font-bold text-sm text-[#0C343D] mb-2">Kata Sandi</label>
                <input id="password" type="password" name="password"
                    required autocomplete="current-password"
                    class="block w-full h-12 px-4 rounded-xl border border-black bg-transparent text-sm
                           focus:border-[#45818E] focus:ring-1 focus:ring-[#45818E]">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <button type="submit"
                class="w-full h-11 bg-[#45818E] hover:bg-[#0C343D] text-white font-inter font-bold text-base rounded-xl transition">
                MASUK
            </button>
        </form>
    </div>
</x-guest-layout>