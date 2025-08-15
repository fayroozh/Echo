<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md my-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">مشاركة الاقتباس</h1>
        
        <div class="quote-card p-6 border border-gray-200 dark:border-gray-700 rounded-lg mb-6">
            <p class="text-xl mb-4">{{ $quote->content }}</p>
            <div class="flex items-center text-gray-800 dark:text-gray-400">
                <span>{{ $quote->user->name }}</span>
                @if($quote->feeling)
                    <span class="mx-2">|</span>
                    <span>#{{ $quote->feeling }}</span>
                @endif
            </div>
        </div>
        
        <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">مشاركة على:</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($shareButtons as $platform => $link)
                <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" 
                   class="share-btn share-{{ strtolower($platform) }} flex items-center justify-center p-3 rounded-lg text-white transition-transform hover:scale-105">
                    <span>{{ ucfirst($platform) }}</span>
                </a>
            @endforeach
        </div>
        
        <div class="mt-8">
            <a href="{{ route('quotes.show', $quote) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                &larr; العودة إلى الاقتباس
            </a>
        </div>
    </div>
</x-app-layout>