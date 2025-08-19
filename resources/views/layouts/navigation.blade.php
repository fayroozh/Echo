<nav x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: localStorage.getItem('sidebarOpen') === 'true', mobileMenuOpen: false }"
    x-init="
        $watch('darkMode', val => {
            localStorage.setItem('darkMode', val)
            if (val) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        });
        $watch('sidebarOpen', val => {
            localStorage.setItem('sidebarOpen', val)
            document.body.classList.toggle('sidebar-open', val)
        });
        if (darkMode) {
            document.documentElement.classList.add('dark')
        }
        document.body.classList.toggle('sidebar-open', sidebarOpen)

        // إغلاف اللوحة الجانبية تلقائيًا عند الانتقال لصفحة جديدة
        window.addEventListener('beforeunload', () => sidebarOpen = false);
     " @click.away="sidebarOpen = false" @navigate.window="sidebarOpen = false">
    <!-- شريط التنقل العلوي - ثابت في الأعلى -->
    <div
        class="fixed top-0 right-0 left-0 z-50 bg-white dark:bg-gray-800 shadow-md border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- الجانب الأيمن: زر القائمة الجانبية واسم التطبيق -->
                <div class="flex items-center">
                    <!-- زر تبديل القائمة الجانبية -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <!-- اسم التطبيق -->
                    <a href="{{ route('welcome') }}" class="flex items-center mr-4">
                        <span class="text-lg font-bold text-gray-800 dark:text-gray-200">Echo | صدى</span>
                    </a>
                </div>

                <!-- الجزء الأوسط: البحث - يظهر فقط في الشاشات الكبيرة -->
                <div class="hidden md:flex flex-1 items-center justify-center px-2 lg:mr-6 lg:justify-end">
                    <div class="max-w-lg w-full">
                        <form action="{{ route('users.search') }}" method="GET" class="flex items-center">
                            <input type="text" name="q"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-l-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                placeholder="ابحث عن مستخدمين...">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                    <!-- رابط البحث المتقدم -->
                    <div class="mr-4">
                        <a href="{{ route('search.advanced') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 px-3 py-2 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                            البحث المتقدم
                        </a>
                    </div>
                </div>

                <!-- زر البحث للشاشات الصغيرة - تحديث لإظهار نموذج البحث بدلاً من الانتقال لصفحة البحث -->
                <div class="flex md:hidden" x-data="{ searchOpen: false }">
                    <button @click="searchOpen = !searchOpen"
                        class="p-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- نموذج البحث للشاشات الصغيرة -->
                    <div x-show="searchOpen" @click.away="searchOpen = false"
                        class="absolute top-16 left-0 right-0 bg-white dark:bg-gray-800 p-4 shadow-md z-50">
                        <form action="{{ route('quotes.search') }}" method="GET" class="flex items-center">
                            <input type="text" name="q"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-l-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                placeholder="ابحث عن اقتباس...">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                        <!-- رابط البحث المتقدم للشاشات الصغيرة -->
                        <div class="mt-3 text-center">
                            <a href="{{ route('search.advanced') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 px-3 py-2 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                البحث المتقدم
                            </a>
                        </div>
                    </div>


                </div>

                <!-- في قسم الجانب الأيسر: زر الوضع الليلي/النهاري وقائمة المستخدم -->
                <div class="flex items-center">

                    <!-- زر تبديل الوضع الليلي/النهاري -->
                    <button @click="darkMode = !darkMode"
                        class="p-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none">
                        <svg x-show="!darkMode" class="h-5 w-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg x-show="darkMode" class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <!-- زر الإشعارات -->
                    @auth
                        <div class="relative mr-2" x-data="{ open: false }">
                            @php
                                $unreadCount = Auth::user()->notifications()->where('read', false)->count();
                            @endphp
                            <button @click="open = !open"
                                class="p-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none relative">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                @if($unreadCount > 0)
                                    <span
                                        class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">{{ $unreadCount }}</span>
                                @endif
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute left-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50"
                                style="display: none;">
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">الإشعارات</h3>
                                        <a href="{{ route('notifications.index') }}"
                                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline">عرض الكل</a>
                                    </div>
                                </div>
                                @php
                                    $recentNotifications = Auth::user()->notifications()->latest()->take(5)->get();
                                @endphp
                                @if($recentNotifications->count() > 0)
                                    <div class="max-h-60 overflow-y-auto">
                                        @foreach($recentNotifications as $notification)
                                            <div
                                                class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->read ? 'opacity-70' : '' }}">
                                                <div class="flex items-start">
                                                    @if(!$notification->read)
                                                        <span class="h-2 w-2 bg-blue-600 rounded-full mt-1 ml-2 flex-shrink-0"></span>
                                                    @endif
                                                    <div>
                                                        <p
                                                            class="text-sm text-gray-800 dark:text-gray-200 {{ $notification->read ? 'text-gray-500 dark:text-gray-400' : 'font-semibold' }}">
                                                            {{ $notification->content }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs text-blue-600 dark:text-blue-400 hover:underline w-full text-center">
                                                تحديد الكل كمقروء
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">لا توجد إشعارات حاليًا</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endauth


                </div>
            </div>
        </div>
    </div>

    <!-- الشريط الجانبي - قابل للطي -->
    <div x-show="sidebarOpen" x-transition:enter="transition-transform duration-300 ease-in-out"
        x-transition:enter-start="transform translate-x-full" x-transition:enter-end="transform translate-x-0"
        x-transition:leave="transition-transform duration-300 ease-in-out"
        x-transition:leave-start="transform translate-x-0" x-transition:leave-end="transform translate-x-full"
        class="fixed inset-y-0 right-0 z-40 w-64 bg-white dark:bg-gray-800 shadow-lg overflow-y-auto border-l border-gray-200 dark:border-gray-700"
        style="margin-top: 64px;">
        <div class="p-6">



            <!-- روابط التنقل في القائمة الجانبية -->
            <nav class="space-y-2">
                <!-- لوحة التحكم -->
                <a href="{{ route('dashboard') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span>لوحة التحكم</span>
                    </div>
                </a>

                <!-- مكتبة الاقتباسات -->
                <a href="{{ route('quotes.index') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('quotes.index') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span>مكتبة الاقتباسات</span>
                    </div>
                </a>



                <!-- المحادثات -->
                <a href="{{ route('conversations.index') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('conversations.index') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <span>المحادثات</span>
                    </div>
                </a>

                <!-- المجتمعات - NEW -->
                <a href="{{ route('communities.index') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('communities.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>المجتمعات</span>
                    </div>
                </a>

                <!-- الملف الشخصي العام -->
                <a href="{{ route('profile.current') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('profile.current') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>الملف الشخصي العام</span>
                    </div>
                </a>


                <!-- Anonymous Feelings - NEW -->
                <a href="{{ route('guest-quotes.create') }}"
                    class="{{ request()->routeIs('guest-quotes.create') ? 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-900 border-r-4 border-blue-500' : 'text-gray-800 hover:bg-gray-50' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-all duration-200">
                    <svg class="{{ request()->routeIs('guest-quotes.create') ? 'text-blue-600' : 'text-gray-600 group-hover:text-gray-500' }} mr-3 h-6 w-6"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="flex-1">اكتب مشاعرك</span>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">جديد</span>
                </a>

                <!-- تفاعلاتي - قسم عادي بدلاً من منسدل -->
                <a href="{{ route('user.activities') }}"
                    class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('user.activities') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span>تفاعلاتي وأنشطتي</span>
                    </div>
                </a>



                @auth

                    <!-- إضافة هذا الكود في القائمة الجانبية للمشرفين -->
                    @if(Auth::user()->is_admin)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                لوحة المشرف</h3>
                            <div class="mt-3 space-y-1">
                                <!-- لوحة تحكم المشرف -->
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block py-2 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                        <span>لوحة تحكم المشرف</span>
                                    </div>
                                </a>

                                <!-- إدارة اقتباسات الزوار -->
                                <a href="{{ route('guest-quotes.manage') }}"
                                    class="block py-2 px-4 rounded transition duration-200 {{ request()->routeIs('guest-quotes.manage') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span>إدارة اقتباسات الزوار</span>
                                    </div>
                                </a>

                                <!-- إدارة البلاغات -->
                                <a href="{{ route('admin.reports') }}"
                                    class="block py-2 px-4 rounded transition duration-200 {{ request()->routeIs('admin.reports') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        <span>إدارة البلاغات</span>
                                    </div>
                                </a>

                                <!-- إدارة التصنيفات -->
                                <a href="{{ route('admin.categories.index') }}"
                                    class="block py-2 px-4 rounded transition duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 12h16M4 18h16"></path>
                                        </svg>
                                        <span>إدارة التصنيفات</span>
                                    </div>
                                </a>

                                <!-- طلبات إنشاء المجتمعات -->
                                <a href="{{ route('admin.community-requests.index') }}"
                                    class="block py-2 px-4 rounded transition duration-200 {{ request()->routeIs('admin.community-requests.*') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7h18M3 12h18M3 17h18"></path>
                                        </svg>
                                        <span>طلبات إنشاء المجتمعات</span>
                                    </div>
                                </a>

                                <!-- رسائل المستخدمين -->
                                <a href="{{ route('admin.messages.index') }}"
                                    class="block py-2 px-4 rounded transition duration-200 {{ request()->routeIs('admin.messages.index') ? 'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span>رسائل المستخدمين</span>
                                    </div>
                                </a>

                            </div>
                        </div>
                    @endif
                    <!-- الملف الشخصي -->
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen"
                            class="w-full flex items-center justify-between py-2.5 px-4 rounded transition duration-200 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                            <div class="flex items-center">
                                <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                    alt="{{ Auth::user()->name }}" class="w-6 h-6 rounded-full ml-2">
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform" :class="{'rotate-180': profileOpen}" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div x-show="profileOpen" class="pr-6 mt-1 space-y-1">
                            <!-- إعدادات الحساب -->
                            <a href="{{ route('profile.edit') }}"
                                class="block py-2 px-4 rounded transition duration-200 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>إعدادات الحساب</span>
                                </div>
                            </a>
                            <!-- تواصل مع المشرف -->
                            <a href="{{ route('contact-admin') }}"
                                class="block py-2 px-4 rounded transition duration-200 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>تواصل مع المشرف</span>
                                </div>
                            </a>

                            <!-- تسجيل الخروج -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-right py-2 px-4 rounded transition duration-200 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <span>تسجيل الخروج</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>

                @endauth
        </div>
    </div>

    <!-- طبقة التعتيم عند فتح القائمة الجانبية في الشاشات الصغيرة -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden"
        style="display: none;"></div>
</nav>
<!-- إضافة هذا الكود قبل زر تسجيل الخروج في القائمة الجانبية -->