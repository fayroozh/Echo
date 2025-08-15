<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('تعديل الاقتباس') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('quotes.update', $quote) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">محتوى الاقتباس</label>
                            <textarea id="content" name="content" rows="4" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                required>{{ old('content', $quote->content) }}</textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="feeling" class="block text-sm font-medium text-gray-700 dark:text-gray-300">الشعور</label>
                            <select id="feeling" name="feeling" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                required>
                                <option value="">اختر الشعور</option>
                                <option value="سعيد" {{ old('feeling', $quote->feeling) == 'سعيد' ? 'selected' : '' }}>😊 سعيد</option>
                                <option value="حزين" {{ old('feeling', $quote->feeling) == 'حزين' ? 'selected' : '' }}>😢 حزين</option>
                                <option value="متفائل" {{ old('feeling', $quote->feeling) == 'متفائل' ? 'selected' : '' }}>🌟 متفائل</option>
                                <option value="محبط" {{ old('feeling', $quote->feeling) == 'محبط' ? 'selected' : '' }}>😔 محبط</option>
                                <option value="ممتن" {{ old('feeling', $quote->feeling) == 'ممتن' ? 'selected' : '' }}>🙏 ممتن</option>
                                <option value="قلق" {{ old('feeling', $quote->feeling) == 'قلق' ? 'selected' : '' }}>😰 قلق</option>
                                <option value="مُلهم" {{ old('feeling', $quote->feeling) == 'مُلهم' ? 'selected' : '' }}>✨ مُلهم</option>
                                <option value="غاضب" {{ old('feeling', $quote->feeling) == 'غاضب' ? 'selected' : '' }}>😠 غاضب</option>
                            </select>
                            @error('feeling')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                تحديث الاقتباس
                            </button>
                            <a href="{{ route('user.quotes') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>