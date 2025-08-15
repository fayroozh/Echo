<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-900 px-4">
        <div class="w-full sm:max-w-md px-6 py-8 bg-white dark:bg-gray-800 shadow-xl rounded-lg">
            
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">انضم إلى مجتمع Echo | صدى</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">أنشئ حسابك الآن وشارك اقتباساتك ومشاعرك مع العالم</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('الاسم')" class="text-sm text-gray-700 dark:text-gray-300" />
                    <x-text-input id="name" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('البريد الإلكتروني')" class="text-sm text-gray-700 dark:text-gray-300" />
                    <x-text-input id="email" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('كلمة المرور')" class="text-sm text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" class="text-sm text-gray-700 dark:text-gray-300" />
                    <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <a class="text-sm text-blue-600 dark:text-blue-400 hover:underline" href="{{ route('login') }}">
                        {{ __('لديك حساب بالفعل؟') }}
                    </a>

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                        {{ __('تسجيل') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
