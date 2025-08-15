<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-900 px-4">
        <div class="w-full sm:max-w-md px-6 py-8 bg-white dark:bg-gray-800 shadow-xl rounded-lg">
            
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Echo | صدى</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    مرحبًا بعودتك! سجل دخولك للوصول إلى اقتباساتك المفضلة
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('البريد الإلكتروني')" class="text-sm text-gray-700 dark:text-gray-300" />
                    <x-text-input id="email" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('كلمة المرور')" class="text-sm text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" name="remember">
                    <label for="remember_me" class="mr-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('تذكرني') }}
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 dark:text-blue-400 hover:underline" href="{{ route('password.request') }}">
                            {{ __('نسيت كلمة المرور؟') }}
                        </a>
                    @endif

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                        {{ __('تسجيل الدخول') }}
                    </x-primary-button>
                </div>

                <!-- Register -->
                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        ليس لديك حساب؟
                        <a href="{{ route('register') }}" class="text-blue-500 dark:text-blue-400 hover:underline">إنشاء حساب جديد</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
