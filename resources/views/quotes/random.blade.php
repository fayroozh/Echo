<x-app-layout>
    <div class="container mx-auto max-w-xl p-6 text-center">
        <h1 class="text-2xl font-bold mb-6">🎲 اقتباس عشوائي</h1>

        @if(isset($quote) && $quote)
            <div class="bg-white dark:bg-gray-800 shadow-md rounded p-4 border border-gray-300 dark:border-gray-700">
                <p class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">{{ $quote->content }}</p>
                @if($quote->feeling)
                    <span class="text-sm text-gray-700 dark:text-gray-300">#{{ $quote->feeling }}</span>
                @endif
                @if($quote->created_at)
                    <span class="text-xs text-gray-800 dark:text-gray-400 float-right block mt-2">
                        {{ $quote->created_at->diffForHumans() }}
                    </span>
                @endif
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-400">لا يوجد اقتباس للعرض حالياً.</p>
        @endif

        <div class="mt-6 space-x-2 space-x-reverse">
            <a href="{{ route('quotes.random') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">اعرض اقتباس آخر</a>
            <a href="{{ route('quotes.index') }}" class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600">العودة للمكتبة</a>
        </div>
    </div>
</x-app-layout>
