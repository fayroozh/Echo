<x-app-layout>

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">أضف اقتباسك</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('quotes.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="content" class="block mb-1 font-semibold">نص الاقتباس</label>
            <textarea name="content" id="content" rows="4" class="w-full border rounded p-2 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="feeling" class="block mb-1 font-semibold">اختر الشعور</label>
            <select name="feeling" id="feeling" class="w-full border rounded p-2 @error('feeling') border-red-500 @enderror">
                <option value="">-- اختر --</option>
                <option value="حب" {{ old('feeling') == 'حب' ? 'selected' : '' }}>حب</option>
                <option value="حزن" {{ old('feeling') == 'حزن' ? 'selected' : '' }}>حزن</option>
                <option value="أمل" {{ old('feeling') == 'أمل' ? 'selected' : '' }}>أمل</option>
                <option value="فرح" {{ old('feeling') == 'فرح' ? 'selected' : '' }}>فرح</option>
                <option value="وحدة" {{ old('feeling') == 'وحدة' ? 'selected' : '' }}>وحدة</option>
                <option value="تأمل" {{ old('feeling') == 'تأمل' ? 'selected' : '' }}>تأمل</option>
            </select>
            @error('feeling')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">أضف الاقتباس</button>
    </form>
</div>

</x-app-layout>