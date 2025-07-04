<x-guest-layout>
   

    <div class="mb-4 text-sm text-gray-600">
        {{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y podrás elegir una nueva.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
              class="block mt-1 w-full"
              style="border: 1px solid #007bff;"
              type="email"
              name="email"
              :value="old('email')"
              required
              autofocus />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button style="background-color: #007bff; border-color: #007bff; text-transform: none; font-family: 'Source Sans Pro', sans-serif;">
                {{ __('Recuperar Clave') }}
            </x-primary-button>

        </div>


    </form>
</x-guest-layout>
