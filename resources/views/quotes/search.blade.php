<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('بحث عن اقتباسات') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- نموذج البحث -->
                    <form action="{{ route('quotes.search') }}" method="GET" class="mb-6">
                        <div class="flex items-center">
                            <input type="text" name="q" value="{{ request('q') }}" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-l-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="ابحث عن اقتباس...">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                    @if(isset($quotes))
                        <!-- نتائج البحث -->
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">
                            {{ __('نتائج البحث') }} ({{ $quotes->total() }})
                        </h3>

                        @if($quotes->count() > 0)
                            <div class="space-y-6">
                                @foreach($quotes as $quote)
                                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center">
                                                <div class="mr-2">
                                                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $quote->user->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-600">{{ $quote->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                            <!-- زر إضافة للمفضلة -->
                                            <button 
                                                onclick="event.preventDefault(); document.getElementById('favorite-form-{{ $quote->id }}').submit();"
                                                class="text-gray-600 hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors duration-200"
                                            >
                                                @if(Auth::check() && Auth::user()->hasFavorited($quote))
                                                    <svg class="h-6 w-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                            <form id="favorite-form-{{ $quote->id }}" action="{{ route('quotes.favorite', $quote) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <p class="text-lg text-gray-800 dark:text-gray-200">"{{ $quote->content }}"</p>
                                            @if($quote->source)
                                                <p class="text-sm text-gray-600 dark:text-gray-600 mt-1">- {{ $quote->source }}</p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <div class="flex space-x-2 space-x-reverse">
                                                <span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-sm text-gray-700 dark:text-gray-300">#{{ $quote->feeling }}</span>
                                            </div>
                                            
                                            <div class="flex space-x-4 space-x-reverse">
                                                <button 
                                                    onclick="event.preventDefault(); document.getElementById('like-form-{{ $quote->id }}').submit();"
                                                    class="flex items-center text-gray-500 hover:text-blue-600 dark:text-gray-600 dark:hover:text-blue-400"
                                                >
                                                    <svg class="h-5 w-5 {{ Auth::check() && Auth::user()->hasLiked($quote) ? 'text-blue-600 dark:text-blue-400 fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                    </svg>
                                                    <span class="mr-1 text-sm">{{ $quote->likes_count ?? 0 }}</span>
                                                </button>
                                                <form id="like-form-{{ $quote->id }}" action="{{ route('quotes.like', $quote) }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                                
                                                <a href="{{ route('quotes.show', $quote) }}" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-600 dark:hover:text-gray-300">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                    </svg>
                                                    <span class="mr-1 text-sm">{{ $quote->comments_count ?? 0 }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- التنقل بين الصفحات -->
                            <div class="mt-6">
                                {{ $quotes->appends(['q' => request('q')])->links() }}
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-600">لم يتم العثور على نتائج مطابقة لبحثك.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>