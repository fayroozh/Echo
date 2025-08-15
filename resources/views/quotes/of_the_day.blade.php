<x-app-layout>
    <div class="container mx-auto max-w-xl p-6 text-center">

        <h1 class="text-2xl font-bold mb-6">🌞 اقتباس اليوم</h1>

        @if ($quote)
            <div class="bg-white shadow-md rounded p-4 border border-gray-300">
                <p class="text-lg font-semibold mb-3">{{ $quote->content }}</p>
                <span class="text-sm text-gray-500">#{{ $quote->feeling }}</span>
                <span class="text-xs text-gray-600 float-right block mt-2">{{ $quote->created_at->diffForHumans() }}</span>
            </div>
        @else
            <p class="text-gray-500">لا يوجد اقتباسات حالياً.</p>
        @endif

        <div class="mt-6">
            <a href="{{ route('quotes.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                العودة للمكتبة
            </a>
        </div>

    </div>
</x-app-layout>
