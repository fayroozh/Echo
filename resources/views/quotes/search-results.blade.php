<x-app-layout>
    <div class="max-w-6xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">البحث المتقدم</h1>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('quotes.search') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- البحث بالنص -->
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نص البحث</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <!-- البحث بالشعور -->
                    <div>
                        <label for="feeling" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الشعور</label>
                        <select name="feeling" id="feeling" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">-- اختر الشعور --</option>
                            @foreach($feelings as $feeling)
                                <option value="{{ $feeling }}" {{ request('feeling') == $feeling ? 'selected' : '' }}>{{ $feeling }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- البحث بالمستخدم -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المستخدم</label>
                        <select name="user_id" id="user_id" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">-- اختر المستخدم --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- البحث بالتاريخ -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">من تاريخ</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">إلى تاريخ</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <!-- البحث بعدد الإعجابات -->
                    <div>
                        <label for="min_likes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الحد الأدنى للإعجابات</label>
                        <input type="number" name="min_likes" id="min_likes" value="{{ request('min_likes') }}" min="0" 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <!-- ترتيب النتائج -->
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ترتيب حسب</label>
                        <select name="sort_by" id="sort_by" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="date" {{ request('sort_by') == 'date' ? 'selected' : '' }}>التاريخ</option>
                            <option value="likes" {{ request('sort_by') == 'likes' ? 'selected' : '' }}>عدد الإعجابات</option>
                            <option value="comments" {{ request('sort_by') == 'comments' ? 'selected' : '' }}>عدد التعليقات</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort_dir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اتجاه الترتيب</label>
                        <select name="sort_dir" id="sort_dir" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                            <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-between mt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        بحث
                    </button>
                    
                    <a href="{{ route('quotes.search') }}" class="px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
        
        <!-- نتائج البحث -->
        <div class="space-y-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                نتائج البحث 
                @if(request()->anyFilled(['q', 'feeling', 'user_id', 'date_from', 'date_to', 'min_likes']))
                    <span class="text-sm font-normal">({{ $quotes->total() }} نتيجة)</span>
                @endif
            </h2>
            
            @forelse ($quotes as $quote)
                <div class="quote-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 transition-all hover:shadow-lg">
                    <p class="text-lg text-gray-800 dark:text-gray-200 mb-4">{{ $quote->content }}</p>
                    
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <a href="{{ route('profile.show', $quote->user) }}" class="flex items-center">
                                @if($quote->user->profile_image)
                                    <img src="{{ asset('storage/' . $quote->user->profile_image) }}" alt="{{ $quote->user->name }}" class="w-8 h-8 rounded-full mr-2">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center mr-2">
                                        <span class="text-gray-800 dark:text-gray-300 text-sm">{{ substr($quote->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="text-gray-700 dark:text-gray-300">{{ $quote->user->name }}</span>
                            </a>
                        </div>
                        
                        <div class="flex items-center space-x-4 space-x-reverse">
                            @if($quote->feeling)
                                <span class="text-sm text-gray-500 dark:text-gray-400">#{{ $quote->feeling }}</span>
                            @endif
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $quote->created_at->diffForHumans() }}</span>
                            <a href="{{ route('quotes.show', $quote) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                عرض
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center mt-4 text-sm text-gray-500 dark:text-gray-400 space-x-4 space-x-reverse">
                        <span>{{ $quote->likes_count ?? $quote->likes()->count() }} إعجاب</span>
                        <span>{{ $quote->comments_count ?? $quote->comments()->count() }} تعليق</span>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">لا يوجد نتائج تطابق البحث.</p>
                </div>
            @endforelse
            
            <div class="mt-6">
                {{ $quotes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
