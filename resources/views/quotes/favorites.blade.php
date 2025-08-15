<x-app-layout>
    <div class="container mx-auto max-w-xl p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">💖 مفضلتي</h1>

        @forelse ($favorites as $quote)
            <div class="bg-white shadow-md rounded p-4 mb-4 border border-gray-300">
                <p class="text-lg font-semibold mb-2">{{ $quote->content }}</p>
                <span class="text-sm text-gray-700">#{{ $quote->feeling }}</span>
                <span class="text-xs text-gray-600 float-right">{{ $quote->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <p class="text-center text-gray-700">لا يوجد اقتباسات مفضلة بعد.</p>
        @endforelse

        <div class="text-center mt-6">
            <a href="{{ route('quotes.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">العودة للمكتبة</a>
        </div>
    </div>
</x-app-layout>
