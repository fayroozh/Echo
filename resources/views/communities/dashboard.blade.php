<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
                {{ __('لوحة تحكم المجتمع') }}: {{ $community->name }}
            </h2>
            <a href="{{ route('communities.show', $community) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('العودة للمجتمع') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- إحصائيات المجتمع -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('الأعضاء') }}</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $community->members_count }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('المتابعين') }}</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $community->followers_count }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('المنشورات') }}</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $community->posts_count }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('التقييم') }}</h3>
                        <div class="flex justify-center items-center">
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 ml-2">{{ number_format($community->rating_avg, 1) }}</p>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- القائمة الجانبية ومحتوى اللوحة -->
            <div class="flex flex-col md:flex-row gap-6">
                <!-- القائمة الجانبية -->
                <div class="w-full md:w-1/4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 text-right">{{ __('القائمة') }}</h3>
                            <nav class="space-y-2">
                                <a href="{{ route('communities.dashboard', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.dashboard') && !request()->query() ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('نظرة عامة') }}
                                </a>
                                <a href="{{ route('communities.posts.pending', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.posts.pending') ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('المنشورات المعلقة') }}
                                    @if($pendingPostsCount > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full mr-2">{{ $pendingPostsCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('communities.requests', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.requests') ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('طلبات الانضمام') }}
                                    @if($pendingMembersCount > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full mr-2">{{ $pendingMembersCount }}</span>
                                    @endif
                                </a>
                                @if($community->is_private)
                                <a href="{{ route('communities.followers.pending', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.followers.pending') ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('طلبات المتابعة') }}
                                    @if($pendingFollowersCount > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full mr-2">{{ $pendingFollowersCount }}</span>
                                    @endif
                                </a>
                                @endif
                                <a href="{{ route('communities.dashboard.members', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.dashboard.members') ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('إدارة الأعضاء') }}
                                </a>
                                <a href="{{ route('communities.dashboard.followers', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.dashboard.followers') ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('المتابعين') }}
                                </a>
                                <a href="{{ route('communities.dashboard.statistics', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.dashboard.statistics') ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('الإحصائيات') }}
                                </a>
                                <a href="{{ route('communities.edit', $community) }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('communities.edit') ? 'bg-blue-500 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} text-right">
                                    {{ __('تعديل المجتمع') }}
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- محتوى اللوحة -->
                <div class="w-full md:w-3/4">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @if(request()->routeIs('communities.dashboard') && !request()->query())
                                <!-- نظرة عامة -->
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 text-right">{{ __('نظرة عامة') }}</h3>
                                
                                <!-- آخر المنشورات -->
                                <div class="mb-6">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3 text-right">{{ __('آخر المنشورات') }}</h4>
                                    @if(count($latestPosts) > 0)
                                        <div class="space-y-3">
                                            @foreach($latestPosts as $post)
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex items-center">
                                                            <img src="{{ $post->user->profile_image_url }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full ml-2">
                                                            <div>
                                                                <p class="text-gray-900 dark:text-gray-100 font-medium text-sm">{{ $post->user->name }}</p>
                                                                <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $post->created_at->diffForHumans() }}</p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                                {{ $post->status === 'approved' ? __('منشور') : __('معلق') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 text-right">
                                                        <h5 class="text-md font-medium text-gray-900 dark:text-gray-100">{{ $post->title }}</h5>
                                                        <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">{{ $post->content }}</p>
                                                    </div>
                                                    <div class="mt-3 flex justify-end">
                                                        <a href="{{ route('communities.show', [$community, 'post' => $post->id]) }}" class="text-blue-600 dark:text-blue-400 text-sm hover:underline">{{ __('عرض المنشور') }}</a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400 text-center">{{ __('لا توجد منشورات بعد.') }}</p>
                                    @endif
                                </div>

                                <!-- آخر الأعضاء المنضمين -->
                                <div class="mb-6">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3 text-right">{{ __('آخر الأعضاء المنضمين') }}</h4>
                                    @if(count($latestMembers) > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($latestMembers as $member)
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <img src="{{ $member->user->profile_image_url }}" alt="{{ $member->user->name }}" class="w-10 h-10 rounded-full ml-3">
                                                        <div>
                                                            <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $member->user->name }}</p>
                                                            <p class="text-gray-500 dark:text-gray-400 text-xs">{{ __('انضم') }} {{ $member->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @if($member->is_moderator)
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                                {{ __('مشرف') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400 text-center">{{ __('لا يوجد أعضاء بعد.') }}</p>
                                    @endif
                                </div>

                                <!-- إجراءات سريعة -->
                                <div>
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3 text-right">{{ __('إجراءات سريعة') }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <a href="{{ route('communities.posts.pending', $community) }}" class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                            <div class="ml-4 bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-right">
                                                <h5 class="text-gray-900 dark:text-gray-100 font-medium">{{ __('المنشورات المعلقة') }}</h5>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('مراجعة وإدارة المنشورات المعلقة') }}</p>
                                            </div>
                                        </a>
                                        <a href="{{ route('communities.requests', $community) }}" class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                            <div class="ml-4 bg-green-100 dark:bg-green-900 p-3 rounded-full">
                                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-right">
                                                <h5 class="text-gray-900 dark:text-gray-100 font-medium">{{ __('طلبات الانضمام') }}</h5>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('إدارة طلبات الانضمام للمجتمع') }}</p>
                                            </div>
                                        </a>
                                        <a href="{{ route('communities.edit', $community) }}" class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                            <div class="ml-4 bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-right">
                                                <h5 class="text-gray-900 dark:text-gray-100 font-medium">{{ __('تعديل المجتمع') }}</h5>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('تعديل معلومات وإعدادات المجتمع') }}</p>
                                            </div>
                                        </a>
                                        <a href="{{ route('communities.dashboard.members', $community) }}" class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                            <div class="ml-4 bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-right">
                                                <h5 class="text-gray-900 dark:text-gray-100 font-medium">{{ __('إدارة الأعضاء') }}</h5>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('إدارة أعضاء المجتمع والمشرفين') }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
