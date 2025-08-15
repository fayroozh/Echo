@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">البحث المتقدم</h1>
        
        <form method="GET" action="{{ route('search.advanced') }}" class="space-y-6">
            <!-- Search Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع البحث</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="types[]" value="quotes" {{ in_array('quotes', request('types', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">الاقتباسات</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="types[]" value="users" {{ in_array('users', request('types', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">المستخدمين</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="types[]" value="communities" {{ in_array('communities', request('types', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">المجتمعات</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="types[]" value="guest_quotes" {{ in_array('guest_quotes', request('types', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">مشاعر الزوار</span>
                    </label>
                </div>
            </div>

            <!-- Search Query -->
            <div>
                <label for="query" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">كلمات البحث</label>
                <input type="text" name="query" id="query" value="{{ request('query') }}" 
                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="ادخل كلمات البحث...">
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">من تاريخ</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">إلى تاريخ</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <!-- Sort Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ترتيب حسب</label>
                    <select name="sort_by" id="sort_by" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>تاريخ الإنشاء</option>
                        <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>تاريخ التحديث</option>
                        <option value="likes_count" {{ request('sort_by') == 'likes_count' ? 'selected' : '' }}>عدد الإعجابات</option>
                        <option value="comments_count" {{ request('sort_by') == 'comments_count' ? 'selected' : '' }}>عدد التعليقات</option>
                    </select>
                </div>
                <div>
                    <label for="sort_direction" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اتجاه الترتيب</label>
                    <select name="sort_direction" id="sort_direction" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">
                    بحث متقدم
                </button>
            </div>
        </form>
    </div>

    <!-- Search Results -->
    @if(request()->has('query') || request()->has('types'))
        <div class="mt-8">
            <!-- Results will be displayed here -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">نتائج البحث</h2>
                <!-- Add search results display logic here -->
            </div>
        </div>
    @endif
</div>
@endsection