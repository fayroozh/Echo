<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white mb-4">المجتمعات</h1>
                    <p class="text-xl text-blue-100 mb-8">انضم إلى مجتمعات تشاركك الاهتمامات</p>
                    <a href="{{ route('communities.create') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                        إنشاء مجتمع جديد
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- فلتر التصنيفات -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 text-right">تصفية حسب التصنيف</h3>
                <div class="flex flex-wrap gap-2 justify-end">
                    <a href="{{ route('communities.index') }}" class="px-4 py-2 rounded-full text-sm transition-all duration-200 {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        الكل
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('communities.index', ['category' => $category->id]) }}" class="px-4 py-2 rounded-full text-sm transition-all duration-200 {{ request('category') == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- المجتمعات المميزة -->
            @if(isset($featuredCommunities) && $featuredCommunities->count() > 0)
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-right">مجتمعات مميزة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredCommunities as $community)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $community->image ? Storage::url($community->image) : asset('images/default-community.jpg') }}" alt="{{ $community->name }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    <div class="absolute top-4 right-4">
                                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium">مميز</span>
                                    </div>
                                    <div class="absolute bottom-4 right-4">
                                        <h4 class="text-white text-xl font-bold mb-1">{{ $community->name }}</h4>
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= ($community->rating_avg ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-white text-sm mr-2">({{ $community->ratings_count ?? 0 }})</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 text-right line-clamp-2">{{ $community->description }}</p>
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center space-x-4 space-x-reverse text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                                </svg>
                                                {{ $community->members_count ?? 0 }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                {{ $community->followers_count ?? 0 }}
                                            </span>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $community->is_private ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                            {{ $community->is_private ? 'خاص' : 'عام' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('communities.show', $community) }}" class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 block">
                                        عرض المجتمع
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- كل المجتمعات -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-right">جميع المجتمعات</h3>
                
                @if($communities->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($communities as $community)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $community->image ? Storage::url($community->image) : asset('images/default-community.jpg') }}" alt="{{ $community->name }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    <div class="absolute top-4 right-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $community->is_private ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                                            {{ $community->is_private ? 'خاص' : 'عام' }}
                                        </span>
                                    </div>
                                    <div class="absolute bottom-4 right-4">
                                        <h4 class="text-white text-xl font-bold mb-1">{{ $community->name }}</h4>
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= ($community->rating_avg ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-white text-sm mr-2">({{ $community->ratings_count ?? 0 }})</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 text-right line-clamp-2">{{ $community->description }}</p>
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center space-x-4 space-x-reverse text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                                </svg>
                                                {{ $community->members_count ?? 0 }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                {{ $community->followers_count ?? 0 }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $community->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex space-x-2 space-x-reverse">
                                        <a href="{{ route('communities.show', $community) }}" class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                            عرض المجتمع
                                        </a>
                                        
                                        @auth
                                            @if(!$community->isMember(auth()->user()))
                                                @if($community->hasPendingJoinRequest(auth()->user()))
                                                    <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg cursor-not-allowed text-sm" disabled>
                                                        طلب معلق
                                                    </button>
                                                @else
                                                    <form action="{{ route('communities.join', $community) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm">
                                                            انضمام
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">عضو</span>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $communities->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100 mb-2">لا توجد مجتمعات</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">ابدأ بإنشاء مجتمع جديد وكن أول من يشارك اهتماماته.</p>
                        <a href="{{ route('communities.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 inline-block">
                            إنشاء مجتمع جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>