<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Masukkan NRP Anda. Jika terdaftar, tautan reset password akan dikirim ke alamat email yang tersimpan di akun (cek inbox / spam).
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="nrp" :value="__('NRP')" />
            <x-text-input id="nrp" class="block mt-1 w-full" type="text" name="nrp" :value="old('nrp')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('nrp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Kirim tautan reset password
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
