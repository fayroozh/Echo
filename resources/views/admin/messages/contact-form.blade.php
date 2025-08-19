<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('التواصل مع المشرف') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact-admin.send') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="subject" :value="__('الموضوع')" />
                            <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" 
                                :value="old('subject')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('subject')" />
                        </div>

                        <div>
                            <x-input-label for="message" :value="__('الرسالة')" />
                            <textarea id="message" name="message" rows="6" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>{{ old('message') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('message')" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-3">
                                {{ __('إرسال الرسالة') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">
                            ملاحظات مهمة:
                        </h3>
                        <ul class="text-blue-700 dark:text-blue-300 text-sm space-y-1">
                            <li>• سيتم إرسال رسالتك لجميع مشرفي الموقع</li>
                            <li>• سيتم الرد عليك في أقرب وقت ممكن</li>
                            <li>• تأكد من كتابة موضوع واضح ورسالة مفصلة</li>
                            <li>• يمكنك استخدام هذه الصفحة للإبلاغ عن مشاكل أو اقتراحات</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
