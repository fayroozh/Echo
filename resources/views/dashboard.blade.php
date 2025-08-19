<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    @php
        $user = Auth::user();
        $quotes = $user->quotes()->latest()->take(5)->get();
        $quotesCount = $user->quotes()->count();
        $favoritesCount = $user->favorites()->count();
        $likesCount = $user->likes()->count();
        $reactionsCount = $user->reactions()->count();
        $profileViews = 150;
        $followersCount = $user->followers()->count();
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ترحيب شخصي -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">مرحباً {{ $user->name }}! 👋</h3>
                        <p class="text-blue-100">استمتع بمشاركة أفكارك وإلهام الآخرين</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ $quotesCount }}</div>
                        <div class="text-blue-100">اقتباس منشور</div>
                    </div>
                </div>
            </div>

            <!-- إحصائيات سريعة محسنة -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $quotesCount }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">اقتباساتي</h3>
                    <a href="{{ route('quotes.create') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">إضافة جديد →</a>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-lg">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $favoritesCount }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">المفضلة</h3>
                    <a href="{{ route('quotes.favorites') }}" class="text-xs text-yellow-600 hover:text-yellow-800 font-medium">عرض الكل →</a>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $likesCount + $reactionsCount }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">التفاعلات</h3>
                    <a href="{{ route('user.activities') }}" class="text-xs text-green-600 hover:text-green-800 font-medium">عرض النشاط →</a>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $followersCount }}</span>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">المتابعون</h3>
                    <a href="{{ route('follows.my-followers') }}" class="text-xs text-purple-600 hover:text-purple-800 font-medium">عرض المتابعين →</a>
                </div>
            </div>

            <!-- روابط سريعة محسنة -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">إجراءات سريعة</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('quotes.create') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                        <div class="bg-blue-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-blue-800 dark:text-blue-200">إضافة اقتباس</span>
                    </a>

                    <a href="{{ route('quotes.index') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                        <div class="bg-green-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-green-800 dark:text-green-200">مكتبة الاقتباسات</span>
                    </a>

                    <a href="{{ route('quotes.random') }}" class="flex flex-col items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors group">
                        <div class="bg-yellow-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">اقتباس عشوائي</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors group">
                        <div class="bg-purple-600 p-3 rounded-full mb-3 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-purple-800 dark:text-purple-200">تعديل الملف</span>
                    </a>
                </div>
            </div>

            <!-- آخر اقتباساتي -->
            @if($quotes->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">آخر اقتباساتي</h3>
                    <a href="{{ route('quotes.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض الكل →</a>
                </div>
                <div class="space-y-4">
                    @foreach($quotes as $quote)
                    <div class="border-r-4 border-blue-500 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-gray-800 dark:text-gray-200 mb-2">"{{ Str::limit($quote->content, 120) }}"</p>
                        <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                            <span class="bg-blue-100 dark:bg-blue-900 px-2 py-1 rounded-full">#{{ $quote->feeling }}</span>
                            <span>{{ $quote->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

