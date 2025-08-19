<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">البحث المتقدم</h1>

            <form method="GET" action="{{ route('search.advanced') }}" class="space-y-6">
                <!-- Search Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع البحث</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="types[]" value="quotes" {{ in_array('quotes', request('types', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">الاقتباسات</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="types[]" value="users" {{ in_array('users', request('types', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">المستخدمين</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="types[]" value="communities" {{ in_array('communities', request('types', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">المجتمعات</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="types[]" value="guest_quotes" {{ in_array('guest_quotes', request('types', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">مشاعر الزوار</span>
                        </label>
                    </div>
                </div>

                <!-- Search Query -->
                <div>
                    <label for="query" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">كلمات
                        البحث</label>
                    <input type="text" name="query" id="query" value="{{ request('query') }}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="ادخل كلمات البحث...">
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="date_from"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">من تاريخ</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">إلى
                            تاريخ</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sort_by"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ترتيب حسب</label>
                        <select name="sort_by" id="sort_by"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>تاريخ
                                الإنشاء</option>
                            <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>تاريخ
                                التحديث</option>
                            <option value="likes_count" {{ request('sort_by') == 'likes_count' ? 'selected' : '' }}>عدد
                                الإعجابات</option>
                            <option value="comments_count" {{ request('sort_by') == 'comments_count' ? 'selected' : '' }}>
                                عدد التعليقات</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort_direction"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اتجاه
                            الترتيب</label>
                        <select name="sort_direction" id="sort_direction"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>تنازلي
                            </option>
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">
                        بحث متقدم
                    </button>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        @if(isset($results) && (isset($results['users']) || isset($results['quotes'])))
            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">نتائج البحث</h2>

                    @if(isset($results['users']) && $results['users']->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">المستخدمين</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($results['users'] as $user)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}"
                                                class="w-10 h-10 rounded-full">
                                            <div>
                                                <a href="{{ route('profile.show', $user->id) }}"
                                                    class="font-semibold text-gray-900 dark:text-white hover:text-blue-600">{{ $user->name }}</a>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{ $results['users']->links() }}
                        </div>
                    @endif

                    @if(isset($results['quotes']) && $results['quotes']->count() > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">الاقتباسات</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($results['quotes'] as $quote)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <blockquote class="text-gray-800 dark:text-gray-200 mb-2">
                                            "{{ \Illuminate\Support\Str::limit($quote->content, 100) }}"</blockquote>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">- {{ $quote->author }}</p>
                                        <a href="{{ route('quotes.show', $quote->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm">عرض التفاصيل</a>
                                    </div>
                                @endforeach
                            </div>
                            {{ $results['quotes']->links() }}
                        </div>
                    @endif

                    @if((!isset($results['users']) || $results['users']->count() == 0) && (!isset($results['quotes']) || $results['quotes']->count() == 0))
                        <p class="text-gray-600 dark:text-gray-400 text-center py-8">لا توجد نتائج للبحث</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>