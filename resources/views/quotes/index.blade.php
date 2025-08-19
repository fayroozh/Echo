<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('مكتبة الاقتباسات') }}
            </h2>

            <!-- زر إضافة اقتباس كنافذة منبثقة -->
            <button type="button"
                onclick="openAddQuoteModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                إضافة اقتباس
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- تصفية المشاعر -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('quotes.index') }}"
                       class="{{ !request('feeling') && !request('category') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} px-3 py-1 rounded-full text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        الكل
                    </a>

                    <!-- المشاعر -->
                    <div class="w-full flex flex-wrap gap-2 mt-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-2">المشاعر:</span>
                        @foreach(['سعادة', 'حزن', 'حماس', 'إلهام', 'تأمل', 'غضب', 'حب','خيبة ', 'خوف', 'أمل'] as $feeling)
                            <a href="{{ route('quotes.index', ['feeling' => $feeling]) }}"
                               class="{{ request('feeling') == $feeling ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} px-3 py-1 rounded-full text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                {{ $feeling }}
                            </a>
                        @endforeach
                    </div>

                    <!-- التصنيفات -->
                    <div class="w-full flex flex-wrap gap-2 mt-2">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-2">التصنيفات:</span>
                        @foreach(\App\Models\Category::all() as $category)
                            <a href="{{ route('quotes.index', ['category' => $category->id]) }}"
                               class="{{ request('category') == $category->id ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} px-3 py-1 rounded-full text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($quotes->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($quotes as $quote)
                        <div id="card-{{ $quote->id }}"
                             class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-visible flex flex-col"
                             data-comment-url="{{ route('quotes.comment', $quote->id) }}"
                             data-like-url="{{ route('quotes.like', $quote->id) }}"
                             data-reaction-url="{{ route('quotes.reaction', $quote->id) }}"
                             data-report-url="{{ route('quotes.report', $quote->id) }}"
                             data-block-url="{{ route('users.block', $quote->user->id) }}"
                             data-show-url="{{ route('quotes.show', $quote->id) }}">
                            <!-- رأس الكارد - معلومات الناشر -->
                            <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <img src="{{ $quote->user->profile_image ? asset('storage/' . $quote->user->profile_image) : asset('images/default-avatar.png') }}"
                                         alt="{{ $quote->user->name }}"
                                         class="w-10 h-10 rounded-full object-cover">
                                    <div>
                                        <a href="{{ route('user.profile', $quote->user->id) }}"
                                           class="font-semibold text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $quote->user->name }}
                                        </a>
                                        <p class="text-xs text-gray-700 dark:text-gray-400">{{ $quote->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <!-- قائمة الخيارات (ثلاث نقاط) -->
                                <div class="relative z-20" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-50 border border-gray-200 dark:border-gray-600">

                                        @auth
                                            <!-- إضافة للمفضلة -->
                                            <form action="{{ route('quotes.favorite', $quote->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ Auth::user()->favorites->contains($quote->id) ? 'إزالة من المفضلة' : 'إضافة للمفضلة' }}
                                                </button>
                                            </form>

                                            <!-- تحميل كصورة -->
                                            <button onclick="downloadQuoteAsImage({{ $quote->id }})" class="flex items-center w-full px-4 py-2 text-sm text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                تحميل كصورة
                                            </button>

                                            @if($quote->user_id !== Auth::id())
                                                <!-- إبلاغ -->
                                                <button onclick="reportQuote({{ $quote->id }})" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                    إبلاغ عن المحتوى
                                                </button>

                                                <!-- حظر المستخدم -->
                                                <button onclick="blockUser({{ $quote->user->id }})" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                    حظر {{ $quote->user->name }}
                                                </button>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>

                            <!-- محتوى الاقتباس -->
                            <div class="p-4">
                                <div class="quote-content" data-quote-id="{{ $quote->id }}">
                                    @php
                                        $isLong = mb_strlen($quote->content) > 200;
                                    @endphp

                                    @if($isLong)
                                        <p class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed mb-4 line-clamp-5">
                                            “{{ $quote->content }}”
                                        </p>
                                        <a href="{{ route('quotes.show', $quote->id) }}"
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                            عرض المزيد
                                        </a>
                                    @else
                                        <p class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed mb-4">“{{ $quote->content }}”</p>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($quote->feeling)
                                        <span class="bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 px-3 py-1 rounded-full text-sm font-medium text-blue-800 dark:text-blue-200">
                                            #{{ $quote->feeling }}
                                        </span>
                                    @endif

                                    @if($quote->category)
                                        <span class="bg-gradient-to-r from-green-100 to-teal-100 dark:from-green-900 dark:to-teal-900 px-3 py-1 rounded-full text-sm font-medium text-green-800 dark:text-green-200">
                                            {{ $quote->category->name }}
                                        </span>
                                    @endif
                                </div>

                                @auth
                                    <!-- أزرار التفاعل -->
                                    <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-4">
                                        <!-- زر التفاعلات -->
                                        <div class="relative z-20" x-data="{ showReactions: false }">
                                            <button @click="showReactions = !showReactions"
                                                    class="flex items-center space-x-1 space-x-reverse px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                                <svg class="w-5 h-5 text-gray-800 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                                </svg>
                                                <span class="text-sm">{{ $quote->likes()->count() + $quote->reactions()->count() }}</span>
                                            </button>

                                            <!-- قائمة التفاعلات -->
                                            <div x-show="showReactions" @click.away="showReactions = false"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 class="absolute bottom-full mb-2 right-0 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 p-2 flex flex-wrap gap-2 z-50 min-w-[220px]">
                                                <!-- إعجاب -->
                                                <button onclick="toggleLike({{ $quote->id }})"
                                                        class="p-2 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors {{ $quote->isLikedBy(Auth::user()) ? 'bg-blue-100 dark:bg-blue-900' : '' }}">
                                                    👍 <span class="text-xs">{{ $quote->likes()->count() }}</span>
                                                </button>
                                                <!-- حب -->
                                                <button onclick="toggleReaction({{ $quote->id }}, 'love')"
                                                        class="p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors {{ $quote->hasReaction(Auth::user(), 'love') ? 'bg-red-100 dark:bg-red-900' : '' }}">
                                                    ❤️ <span class="text-xs">{{ $quote->reactions()->where('type', 'love')->count() }}</span>
                                                </button>
                                                <!-- سعادة -->
                                                <button onclick="toggleReaction({{ $quote->id }}, 'happy')"
                                                        class="p-2 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900 transition-colors {{ $quote->hasReaction(Auth::user(), 'happy') ? 'bg-yellow-100 dark:bg-yellow-900' : '' }}">
                                                    😊 <span class="text-xs">{{ $quote->reactions()->where('type', 'happy')->count() }}</span>
                                                </button>
                                                <!-- دهشة -->
                                                <button onclick="toggleReaction({{ $quote->id }}, 'wow')"
                                                        class="p-2 rounded-full hover:bg-purple-100 dark:hover:bg-purple-900 transition-colors {{ $quote->hasReaction(Auth::user(), 'wow') ? 'bg-purple-100 dark:bg-purple-900' : '' }}">
                                                    😮 <span class="text-xs">{{ $quote->reactions()->where('type', 'wow')->count() }}</span>
                                                </button>
                                                <!-- حزن -->
                                                <button onclick="toggleReaction({{ $quote->id }}, 'sad')"
                                                        class="p-2 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors {{ $quote->hasReaction(Auth::user(), 'sad') ? 'bg-blue-100 dark:bg-blue-900' : '' }}">
                                                    😢 <span class="text-xs">{{ $quote->reactions()->where('type', 'sad')->count() }}</span>
                                                </button>
                                                <!-- غضب -->
                                                <button onclick="toggleReaction({{ $quote->id }}, 'angry')"
                                                        class="p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors {{ $quote->hasReaction(Auth::user(), 'angry') ? 'bg-red-100 dark:bg-red-900' : '' }}">
                                                    😠 <span class="text-xs">{{ $quote->reactions()->where('type', 'angry')->count() }}</span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- زر التعليقات -->
                                        <button onclick="openCommentModal({{ $quote->id }})"
                                                class="flex items-center space-x-1 space-x-reverse px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                            <svg class="w-5 h-5 text-gray-800 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            <span class="text-sm">{{ $quote->comments->count() }}</span>
                                        </button>

                                        <!-- زر المشاركة -->
                                        <div class="relative z-20" x-data="{ showShare: false }">
                                            <button @click="showShare = !showShare"
                                                    class="flex items-center space-x-1 space-x-reverse px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                                <svg class="w-5 h-5 text-gray-800 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                                </svg>
                                                <span class="text-sm">مشاركة</span>
                                            </button>

                                            <!-- قائمة المشاركة -->
                                            <div x-show="showShare" @click.away="showShare = false"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 class="absolute bottom-full mb-2 left-0 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 p-2 min-w-max z-50">
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('quotes.show', $quote->id)) }}"
                                                   target="_blank"
                                                   class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded transition-colors">
                                                    <span class="ml-2">📘</span> فيسبوك
                                                </a>
                                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($quote->content) }}&url={{ urlencode(route('quotes.show', $quote->id)) }}"
                                                   target="_blank"
                                                   class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded transition-colors">
                                                    <span class="ml-2">🐦</span> تويتر
                                                </a>
                                                <a href="https://wa.me/?text={{ urlencode($quote->content . ' - ' . route('quotes.show', $quote->id)) }}"
                                                   target="_blank"
                                                   class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded transition-colors">
                                                    <span class="ml-2">💬</span> واتساب
                                                </a>
                                                <a href="{{ route('conversations.index', ['share_quote' => $quote->id]) }}"
                                                   class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded transition-colors">
                                                    <span class="ml-2">💌</span> مشاركة في محادثة
                                                </a>
                                                <button onclick="copyToClipboard('{{ route('quotes.show', $quote->id) }}')"
                                                        class="flex items-center w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded transition-colors">
                                                    <span class="ml-2">🔗</span> نسخ الرابط
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>

                        <!-- نافذة التعليقات المنبثقة -->
                        <div id="commentModal-{{ $quote->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
                            <div class="bg-white dark:bg-gray-800 rounded-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
                                <!-- رأس النافذة -->
                                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">تفاصيل الاقتباس</h3>
                                    <button onclick="closeCommentModal({{ $quote->id }})" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- محتوى النافذة -->
                                <div class="p-4 max-h-96 overflow-y-auto">
                                    <!-- الاقتباس -->
                                    <div class="mb-6">
                                        <div class="flex items-center space-x-3 space-x-reverse mb-3">
                                            <img src="{{ $quote->user->profile_image ? asset('storage/' . $quote->user->profile_image) : asset('images/default-avatar.png') }}"
                                                 alt="{{ $quote->user->name }}"
                                                 class="w-10 h-10 rounded-full object-cover">
                                            <div>
                                                <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ $quote->user->name }}</h4>
                                                <p class="text-xs text-gray-700 dark:text-gray-400">{{ $quote->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <p class="text-lg text-gray-800 dark:text-gray-200 leading-relaxed mb-3">“{{ $quote->content }}”</p>
                                        <div class="flex flex-wrap gap-2">
                                            @if($quote->feeling)
                                                <span class="bg-blue-100 dark:bg-blue-900 px-3 py-1 rounded-full text-sm text-blue-800 dark:text-blue-200">#{{ $quote->feeling }}</span>
                                            @endif
                                            @if($quote->category)
                                                <span class="bg-purple-100 dark:bg-purple-900 px-3 py-1 rounded-full text-sm text-purple-800 dark:text-purple-200">{{ $quote->category->name }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- نموذج إضافة تعليق -->
                                    @auth
                                        <form id="commentForm-{{ $quote->id }}" class="mb-6">
                                            @csrf
                                            <textarea name="comment" rows="3"
                                                      class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100"
                                                      placeholder="اكتب تعليقك هنا..." required></textarea>
                                            <button type="button" onclick="submitComment({{ $quote->id }})"
                                                    class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                نشر التعليق
                                            </button>
                                        </form>
                                    @endauth

                                    <!-- عرض التعليقات -->
                                    <div class="space-y-3" id="comments-container-{{ $quote->id }}">
                                        <h4 class="font-medium text-gray-800 dark:text-gray-200 mb-3">التعليقات (<span id="comments-count-{{ $quote->id }}">{{ $quote->comments->count() }}</span>)</h4>
                                        @forelse ($quote->comments as $comment)
                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                <div class="flex items-center space-x-3 space-x-reverse mb-2">
                                                    <img src="{{ $comment->user->profile_image ? asset('storage/' . $comment->user->profile_image) : asset('images/default-avatar.png') }}"
                                                         alt="{{ $comment->user->name }}"
                                                         class="w-8 h-8 rounded-full object-cover">
                                                    <div>
                                                        <h5 class="font-medium text-gray-800 dark:text-gray-200">{{ $comment->user->name }}</h5>
                                                    </div>
                                                </div>
                                                <p class="text-gray-800 dark:text-gray-200">{{ $comment->comment }}</p>
                                                <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                            </div>
                                        @empty
                                            <p class="text-gray-700 dark:text-gray-400 text-center py-4" id="no-comments-{{ $quote->id }}">لا يوجد تعليقات بعد</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @endif

                <!-- Pagination links -->
                <div class="mt-6">
                    {{ $quotes->links() }}
                </div>
        </div>
    </div>

    <!-- نافذة إضافة اقتباس -->
    <!-- نافذة إضافة اقتباس -->
    <div id="addQuoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-xl w-full overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">إضافة اقتباس</h3>
                <button onclick="closeAddQuoteModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('quotes.store') }}" method="POST" class="p-4 space-y-3">
                @csrf
                <textarea name="content" rows="4" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 resize-y focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100" placeholder="اكتب اقتباسك هنا..." required></textarea>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input type="text" name="feeling" placeholder="الشعور (اختياري)" class="border border-gray-300 dark:border-gray-600 rounded-lg p-3 dark:bg-gray-700 dark:text-gray-100">
                    <select name="category_id" class="border border-gray-300 dark:border-gray-600 rounded-lg p-3 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">— اختر تصنيفاً (اختياري) —</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeAddQuoteModal()" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">إلغاء</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">نشر</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // فتح وإغلاق نافذة التعليقات
        function openCommentModal(quoteId) {
            document.getElementById('commentModal-' + quoteId).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeCommentModal(quoteId) {
            document.getElementById('commentModal-' + quoteId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // فتح وإغلاق نافذة إضافة اقتباس
        function openAddQuoteModal() {
            document.getElementById('addQuoteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeAddQuoteModal() {
            document.getElementById('addQuoteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // إرسال نموذج إضافة اقتباس باستخدام AJAX
        function submitQuote() {
            const form = document.getElementById('addQuoteForm');
            const formData = new FormData(form);
            
            fetch('{{ route('quotes.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // إغلاق النافذة المنبثقة
                    closeAddQuoteModal();
                    
                    // إعادة تحميل الصفحة لعرض الاقتباس الجديد
                    window.location.reload();
                } else {
                    alert(data.message || 'حدث خطأ أثناء إضافة الاقتباس');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إضافة الاقتباس');
            });
        }

        // نسخ الرابط إلى الحافظة
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('تم نسخ الرابط!');
            });
        }

        // تنزيل الاقتباس كصورة
        function downloadQuoteAsImage(quoteId) {
            if (typeof html2canvas === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                script.onload = function() { captureAndDownload(quoteId); };
                document.head.appendChild(script);
            } else {
                captureAndDownload(quoteId);
            }
        }
        function captureAndDownload(quoteId) {
            const card = document.getElementById('card-' + quoteId);
            if (!card) {
                alert('لم يتم العثور على الاقتباس');
                return;
            }
            html2canvas(card, {
                backgroundColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                scale: 2,
                logging: false,
                useCORS: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'quote-' + quoteId + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }).catch(err => {
                console.error('خطأ في تحويل الاقتباس إلى صورة:', err);
                alert('حدث خطأ أثناء تحميل الصورة');
            });
        }

        // أدوات مساعدة لقراءة روابط المسارات من الكارد
        function getCard(quoteId) { return document.getElementById('card-' + quoteId); }
        function cardUrl(quoteId, key) { return getCard(quoteId).dataset[key]; }

        // إرسال تعليق باستخدام AJAX (يتجنب 500 لو الـ Controller يعيد JSON)
        function submitComment(quoteId) {
            const form = document.getElementById('commentForm-' + quoteId);
            const content = form.querySelector('textarea[name="comment"]').value;
            const token = form.querySelector('input[name="_token"]').value;
            const url = cardUrl(quoteId, 'commentUrl');

            if (!content.trim()) { alert('يرجى كتابة تعليق'); return; }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ comment: content })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const commentsContainer = document.getElementById('comments-container-' + quoteId);
                    const noCommentsElement = document.getElementById('no-comments-' + quoteId);
                    if (noCommentsElement) noCommentsElement.remove();

                    const imgSrc = (data.comment.user.profile_image
                                    ? (data.comment.user.profile_image.startsWith('http') ? data.comment.user.profile_image : ('/storage/' + data.comment.user.profile_image))
                                    : '/storage/images/default-avatar.png');

                    const commentElement = document.createElement('div');
                    commentElement.className = 'bg-gray-50 dark:bg-gray-700 p-3 rounded-lg';
                    commentElement.innerHTML = `
                        <div class="flex items-center space-x-3 space-x-reverse mb-2">
                            <img src="${imgSrc}" alt="${data.comment.user.name}" class="w-8 h-8 rounded-full object-cover">
                            <div><h5 class="font-medium text-gray-800 dark:text-gray-200">${data.comment.user.name}</h5></div>
                        </div>
                        <p class="text-gray-800 dark:text-gray-200">${data.comment.content}</p>
                        <p class="text-xs text-gray-700 dark:text-gray-400 mt-1">الآن</p>
                    `;
                    commentsContainer.appendChild(commentElement);

                    const countElement = document.getElementById('comments-count-' + quoteId);
                    countElement.textContent = parseInt(countElement.textContent) + 1;
                    form.querySelector('textarea[name="content"]').value = '';

                    // تحديث عدد التعليقات الظاهر على الكارد
                    const mainPageCount = document.querySelector(`button[onclick="openCommentModal(${quoteId})"] span`);
                    if (mainPageCount) mainPageCount.textContent = parseInt(mainPageCount.textContent) + 1;
                } else {
                    alert(data.message || 'حدث خطأ أثناء إضافة التعليق');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('تحقق من مسار التعليقات في السيرفر؛ الطلب فشل.');
            });
        }

        // تبديل الإعجاب
        function toggleLike(quoteId) {
            const url = cardUrl(quoteId, 'likeUrl');
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // تحديث لون/عدد زر الإعجاب
                    const buttons = document.querySelectorAll(`button[onclick="toggleLike(${quoteId})"]`);
                    buttons.forEach(btn => {
                        if (data.liked) btn.classList.add('bg-blue-100', 'dark:bg-blue-900');
                        else btn.classList.remove('bg-blue-100', 'dark:bg-blue-900');
                        const span = btn.querySelector('span'); if (span) span.textContent = data.count;
                    });
                    updateTotalReactions(quoteId);
                } else {
                    alert(data.message || 'تعذر تنفيذ الإعجاب');
                }
            })
            .catch(e => { console.error(e); alert('تحقق من مسار الإعجاب في السيرفر'); });
        }

        // تبديل التفاعل
        function toggleReaction(quoteId, type) {
            const url = cardUrl(quoteId, 'reactionUrl');
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const reactionButtons = document.querySelectorAll(`button[onclick="toggleReaction(${quoteId}, '${type}')"]`);
                    reactionButtons.forEach(button => {
                        if (data.reacted) {
                            switch (type) {
                                case 'love':  button.classList.add('bg-red-100','dark:bg-red-900'); break;
                                case 'happy': button.classList.add('bg-yellow-100','dark:bg-yellow-900'); break;
                                case 'wow':   button.classList.add('bg-purple-100','dark:bg-purple-900'); break;
                                case 'sad':   button.classList.add('bg-blue-100','dark:bg-blue-900'); break;
                                case 'angry': button.classList.add('bg-red-100','dark:bg-red-900'); break;
                            }
                        } else {
                            button.classList.remove('bg-red-100','dark:bg-red-900','bg-yellow-100','dark:bg-yellow-900','bg-purple-100','dark:bg-purple-900','bg-blue-100','dark:bg-blue-900');
                        }
                        const span = button.querySelector('span'); if (span) span.textContent = data.count;
                    });
                    updateTotalReactions(quoteId);
                } else {
                    alert(data.message || 'تعذر تسجيل التفاعل');
                }
            })
            .catch(e => { console.error(e); alert('تحقق من مسار التفاعلات في السيرفر'); });
        }

        // تحديث إجمالي التفاعلات
        function updateTotalReactions(quoteId) {
            const likeCount  = parseInt(document.querySelector(`button[onclick="toggleLike(${quoteId})"] span`)?.textContent || '0');
            const loveCount  = parseInt(document.querySelector(`button[onclick="toggleReaction(${quoteId}, 'love')"] span`)?.textContent || '0');
            const happyCount = parseInt(document.querySelector(`button[onclick="toggleReaction(${quoteId}, 'happy')"] span`)?.textContent || '0');
            const wowCount   = parseInt(document.querySelector(`button[onclick="toggleReaction(${quoteId}, 'wow')"] span`)?.textContent || '0');
            const sadCount   = parseInt(document.querySelector(`button[onclick="toggleReaction(${quoteId}, 'sad')"] span`)?.textContent || '0');
            const angryCount = parseInt(document.querySelector(`button[onclick="toggleReaction(${quoteId}, 'angry')"] span`)?.textContent || '0');
            const totalCount = likeCount + loveCount + happyCount + wowCount + sadCount + angryCount;

            const totalElement = getCard(quoteId).querySelector('div[x-data] > button span');
            if (totalElement) totalElement.textContent = totalCount;
        }

        // الإبلاغ عن اقتباس
        function reportQuote(quoteId) {
            if (!confirm('هل أنت متأكد من الإبلاغ عن هذا الاقتباس؟')) return;
            const url = cardUrl(quoteId, 'reportUrl');
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => { alert(data.message || 'تم تنفيذ الإبلاغ'); })
            .catch(e => { console.error(e); alert('تحقق من مسار الإبلاغ في السيرفر'); });
        }

        // حظر مستخدم
        function blockUser(userId) {
            if (!confirm('هل أنت متأكد من حظر هذا المستخدم؟')) return;

            // نأخذ أي كارد يخص هذا المستخدم للحصول على الرابط (أول واحد يكفي)
            const card = document.querySelector(`[data-block-url*="/users/${userId}/"]`) || document.querySelector(`[data-block-url]`);
            const url = card ? card.dataset.blockUrl : null;
            if (!url) { alert('مسار الحظر غير معرف'); return; }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('تم حظر المستخدم بنجاح');
                    window.location.reload();
                } else {
                    alert(data.message || 'تعذر حظر المستخدم');
                }
            })
            .catch(e => { console.error(e); alert('تحقق من مسار الحظر في السيرفر'); });
        }

        // تصحيح مسار الصور الافتراضية
        document.addEventListener('DOMContentLoaded', function() {
            const defaultAvatarImages = document.querySelectorAll('img[src="/images/default-avatar.png"]');
            defaultAvatarImages.forEach(img => {
                img.src = "/storage/images/default-avatar.png";
                img.onerror = function() { this.src = "https://ui-avatars.com/api/?name=User&background=random"; };
            });
        });
    </script>
</x-app-layout>
