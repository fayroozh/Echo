<x-app-layout>
    <!-- صورة الغلاف والمعلومات الأساسية -->
    <div class="relative bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <!-- صورة الغلاف -->
        <div class="h-64 w-full overflow-hidden relative group">
            @if($user->cover_image)
                <img src="{{ asset('storage/' . $user->cover_image) }}" alt="صورة الغلاف"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600"></div>
            @endif

            @if(Auth::id() == $user->id)
                <!-- زر تحديث صورة الغلاف -->
                <div
                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <form action="{{ route('profile.update-cover') }}" method="POST" enctype="multipart/form-data"
                        class="text-center">
                        @csrf
                        <label for="cover_image"
                            class="cursor-pointer bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center">
                            <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            تحديث صورة الغلاف
                        </label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*" class="hidden"
                            onchange="this.form.submit()">
                    </form>
                </div>
            @endif
        </div>

        <!-- معلومات المستخدم والصورة الشخصية -->
        <div class="relative px-6 pb-6">
            <div class="flex flex-col md:flex-row items-center md:items-end -mt-16">
                <!-- الصورة الشخصية -->
                <div class="relative">
                    <div
                        class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 overflow-hidden bg-white dark:bg-gray-700 shadow-xl">
                        @if($user->profile_image)
                            <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}"
                                class="h-full w-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF"
                                alt="{{ $user->name }}" class="h-full w-full object-cover">
                        @endif
                    </div>
                </div>

                <!-- معلومات المستخدم -->
                <div class="md:mr-6 mt-4 md:mt-0 text-center md:text-right flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $user->name }}
                    </h1>
                    @if($user->bio)
                        <p class="text-gray-800 dark:text-gray-400 mb-3 max-w-md">{{ $user->bio }}</p>
                    @endif
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm">
                        @if($user->birthdate)
                            <div class="flex items-center text-gray-800 dark:text-gray-400">
                                <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') }}
                            </div>
                        @endif
                        @if($user->location)
                            <div class="flex items-center text-gray-800 dark:text-gray-400">
                                <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $user->location }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- أزرار التفاعل والإعدادات -->
                <div class="mt-4 md:mt-0 flex flex-col gap-2">
                    @if(Auth::id() == $user->id)
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 transition-colors duration-200">
                            <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            تعديل الملف الشخصي
                        </a>

                        <!-- إعدادات الحساب -->
                        <div class="relative" x-data="{ settingsOpen: false }">
                            <button @click="settingsOpen = !settingsOpen"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-white hover:bg-gray-700 transition-colors duration-200 w-full">
                                <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                إعدادات الحساب
                            </button>

                            <div x-show="settingsOpen" @click.away="settingsOpen = false"
                                class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-600">
                                <!-- تسجيل الخروج -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-right px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center">
                                        <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col gap-2">
                            <form action="{{ route('follows.toggle', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full px-6 py-2 rounded-lg {{ $isFollowing ? 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' : 'bg-blue-600 text-white' }} hover:opacity-90 transition-all duration-200 font-semibold">
                                    {{ $isFollowing ? 'إلغاء المتابعة' : 'متابعة' }}
                                </button>
                            </form>

                            <form action="{{ route('conversations.start', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-semibold">
                                    <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    مراسلة
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات المستخدم -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="grid grid-cols-3 gap-6 text-center">
                <a href="{{ route('follows.followers', ['user' => $user->id]) }}"
                    class="flex flex-col items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $followersCount }}</span>
                    <span class="text-gray-800 dark:text-gray-400 text-lg">متابع</span>
                </a>
                <a href="{{ route('follows.following', ['user' => $user->id]) }}"
                    class="flex flex-col items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $followingCount }}</span>
                    <span class="text-gray-800 dark:text-gray-400 text-lg">يتابع</span>
                </a>
                <div class="flex flex-col items-center">
                    <span
                        class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $user->quotes->count() }}</span>
                    <span class="text-gray-800 dark:text-gray-400 text-lg">اقتباس</span>
                </div>
            </div>
        </div>
    </div>

    <!-- اقتباسات المستخدم -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">اقتباسات {{ $user->name }}</h2>
        @if($quotes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quotes as $quote)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                        <!-- رأس البطاقة -->
                        <div class="p-4 flex items-start justify-between border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                    alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                <div>
                                    <a href="{{ route('user.profile', $user->id) }}"
                                        class="font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $user->name }}
                                    </a>
                                    <p class="text-sm text-gray-700 dark:text-gray-400">
                                        {{ $quote->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <!-- قائمة الخيارات -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                        </path>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-600">
                                    @auth
                                        <!-- إضافة للمفضلة -->
                                        <form action="{{ route('quotes.favorite', $quote->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-right px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center">
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                    </path>
                                                </svg>
                                                إضافة للمفضلة
                                            </button>
                                        </form>

                                        @if(Auth::id() == $quote->user_id)
                                            <!-- تعديل -->
                                            <a href="{{ route('quotes.edit', $quote) }}"
                                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                ✏️ تعديل
                                            </a>
                                            <!-- حذف -->
                                            <form action="{{ route('quotes.destroy', $quote) }}" method="POST" class="block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('هل أنت متأكد من حذف هذا الاقتباس؟')"
                                                    class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    🗑️ حذف
                                                </button>
                                            </form>
                                        @endif

                                        <!-- تحميل كصورة -->
                                        <button onclick="downloadAsImage({{ $quote->id }})"
                                            class="w-full text-right px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center">
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            تحميل كصورة
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- محتوى الاقتباس -->
                        <div class="p-4">
                            <p class="text-gray-900 dark:text-gray-100 text-lg leading-relaxed mb-3">"{{ $quote->content }}"</p>
                            @if($quote->feeling)
                                <span
                                    class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-3 py-1 rounded-full">
                                    #{{ $quote->feeling }}
                                </span>
                            @endif
                        </div>

                        <!-- أزرار التفاعل -->
                        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-6 space-x-reverse">
                                    @auth
                                        <!-- زر الإعجاب -->
                                        <form action="{{ route('quotes.like', $quote->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center space-x-1 space-x-reverse text-sm {{ $quote->likes->contains('user_id', Auth::id()) ? 'text-red-500' : 'text-gray-700 hover:text-red-500' }} transition-colors">
                                                <svg class="w-5 h-5"
                                                    fill="{{ $quote->likes->contains('user_id', Auth::id()) ? 'currentColor' : 'none' }}"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                    </path>
                                                </svg>
                                                <span>{{ $quote->likes->count() }}</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="flex items-center space-x-1 space-x-reverse text-sm text-gray-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                                </path>
                                            </svg>
                                            <span>{{ $quote->likes->count() }}</span>
                                        </span>
                                    @endauth

                                    <!-- زر التعليقات -->
                                    <button onclick="openCommentsModal({{ $quote->id }})"
                                        class="flex items-center space-x-1 space-x-reverse text-sm text-gray-700 hover:text-blue-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                            </path>
                                        </svg>
                                        <span>{{ $quote->comments->count() }}</span>
                                    </button>

                                    <!-- زر المشاركة -->
                                    <button onclick="shareQuote({{ $quote->id }})"
                                        class="flex items-center space-x-1 space-x-reverse text-sm text-gray-700 hover:text-green-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                            </path>
                                        </svg>
                                        <span>مشاركة</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $quotes->links() }}
            </div>
        @else
            <p class="text-center text-gray-700 dark:text-gray-400 py-8">لم يقم {{ $user->name }} بإضافة أي اقتباسات بعد.
            </p>
        @endif
    </div>

    <!-- مجتمعات المستخدم -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">مجتمعات {{ $user->name }}</h2>
        @if($communities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($communities as $community)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                        <!-- صورة المجتمع -->
                        <div class="h-48 w-full overflow-hidden">
                            @if($community->image)
                                <img src="{{ asset('storage/' . $community->image) }}" alt="{{ $community->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-white text-lg font-semibold">{{ substr($community->name, 0, 2) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- معلومات المجتمع -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                <a href="{{ route('communities.show', $community->id) }}"
                                    class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    {{ $community->name }}
                                </a>
                            </h3>

                            @if($community->description)
                                <p class="text-gray-700 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                    {{ Str::limit($community->description, 100) }}
                                </p>
                            @endif

                            <!-- إحصائيات المجتمع -->
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                    <span>{{ $community->members()->count() }} عضو</span>
                                </div>

                                @if($community->pivot && $community->pivot->role)
                                    <span
                                        class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs">
                                        {{ $community->pivot->role == 'admin' ? 'مدير' : 'عضو' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($communities->count() > 5)
                <div class="mt-6 text-center">
                    <a href="{{ route('communities.index') }}?user={{ $user->id }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-semibold">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                            </path>
                        </svg>
                        عرض جميع المجتمعات
                    </a>
                </div>
            @endif
        @else
            <p class="text-center text-gray-700 dark:text-gray-400 py-8">لم ينضم {{ $user->name }} إلى أي مجتمع بعد.</p>
        @endif
    </div>
</x-app-layout>