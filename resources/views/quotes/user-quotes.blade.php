<x-app-layout>
    <div class="container mx-auto p-4 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-center">اقتباساتي</h1>
        
        @if($quotes->count() > 0)
            <div class="space-y-6">
                @foreach ($quotes as $quote)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                        <p class="text-xl font-semibold mb-3 break-words leading-relaxed text-gray-800 dark:text-gray-200">{{ $quote->content }}</p>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                            <span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">#{{ $quote->feeling }}</span>
                            <span>{{ $quote->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                    <svg class="h-5 w-5 ml-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $quote->favoritedBy()->count() }} مفضلة
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                    <svg class="h-5 w-5 ml-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                    </svg>
                                    {{ $quote->likes()->count() }} إعجاب
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                    <svg class="h-5 w-5 ml-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $quote->comments()->count() }} تعليق
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $quotes->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                <p class="text-gray-500 dark:text-gray-400 text-lg">لم تقم بإضافة أي اقتباسات حتى الآن.</p>
                <a href="{{ route('quotes.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    أضف أول اقتباس
                </a>
            </div>
        @endif
    </div>
</x-app-layout>