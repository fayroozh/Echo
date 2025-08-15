<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('إنشاء مجتمع جديد') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('communities.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="text-right">
                            <x-input-label for="name" :value="__('اسم المجتمع')" />
                            <x-text-input id="name" class="block mt-1 w-full text-right" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <x-input-label for="description" :value="__('وصف المجتمع')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-right" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <x-input-label for="idea" :value="__('فكرة المجتمع')" />
                            <textarea id="idea" name="idea" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-right" required>{{ old('idea') }}</textarea>
                            <x-input-error :messages="$errors->get('idea')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <x-input-label for="category_id" :value="__('التصنيف')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-right" required>
                                <option value="">{{ __('اختر تصنيف') }}</option>

                                @if(!empty($categories) && $categories->count() > 0)
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                    <option value="0" {{ old('category_id') === '0' ? 'selected' : '' }}>
                                        {{ __('لا يوجد تصنيف') }}
                                    </option>
                                @else
                                    <option value="0" selected>{{ __('لا يوجد تصنيف') }}</option>
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <x-input-label for="image" :value="__('صورة المجتمع')" />
                            <input id="image" type="file" name="image" class="block mt-1 w-full text-gray-900 dark:text-gray-100" accept="image/*" />
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('الصورة اختيارية. الحد الأقصى للحجم: 2 ميجابايت. الأنواع المسموحة: JPG, PNG, GIF.') }}</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="text-right">
                            <x-input-label :value="__('خصوصية المجتمع')" />
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <input id="is_private_0" type="radio" name="is_private" value="0" class="ml-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 h-4 w-4 text-indigo-600" {{ old('is_private', '0') === '0' ? 'checked' : '' }} />
                                    <label for="is_private_0" class="text-gray-900 dark:text-gray-100">{{ __('عام - يمكن لأي شخص متابعة المجتمع ومشاهدة المنشورات') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="is_private_1" type="radio" name="is_private" value="1" class="ml-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 h-4 w-4 text-indigo-600" {{ old('is_private') === '1' ? 'checked' : '' }} />
                                    <label for="is_private_1" class="text-gray-900 dark:text-gray-100">{{ __('خاص - يتطلب موافقة المالك للمتابعة ومشاهدة المنشورات') }}</label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('is_private')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('إرسال طلب إنشاء المجتمع') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
