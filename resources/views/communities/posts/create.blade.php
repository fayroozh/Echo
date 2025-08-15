<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('إنشاء منشور جديد في مجتمع') }}: {{ $community->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('communities.posts.store', $community) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="text-right">
                            <x-input-label for="title" :value="__('عنوان المنشور')" />
                            <x-text-input id="title" class="block mt-1 w-full text-right" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <x-input-label for="content" :value="__('محتوى المنشور')" />
                            <textarea id="content" name="content" rows="6" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-right" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <x-input-label for="image" :value="__('صورة (اختياري)')" />
                            <input id="image" type="file" name="image" class="block mt-1 w-full text-gray-900 dark:text-gray-100" accept="image/*" />
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('الصورة اختيارية. الحد الأقصى للحجم: 2 ميجابايت. الأنواع المسموحة: JPG, PNG, GIF.') }}</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{ __('ملاحظة: سيتم إرسال منشورك للمراجعة من قبل مالك المجتمع قبل نشره.') }}
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('communities.show', $community) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-3">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('نشر') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>