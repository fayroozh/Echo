<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">إعدادات الحساب</h2>
                    
                    <form method="POST" action="{{ route('profile.settings.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- إعدادات الإشعارات -->
                        <div class="mt-6">
                            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                                <svg class="h-5 w-5 ml-2 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                إعدادات الإشعارات
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="notification_comments" name="notification_comments" type="checkbox" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ auth()->user()->notification_comments ? 'checked' : '' }}>
                                    <label for="notification_comments" class="mr-2 block text-sm text-gray-800 dark:text-gray-300">
                                        إشعارات التعليقات
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="notification_likes" name="notification_likes" type="checkbox" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ auth()->user()->notification_likes ? 'checked' : '' }}>
                                    <label for="notification_likes" class="mr-2 block text-sm text-gray-700 dark:text-gray-300">
                                        إشعارات الإعجابات
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="notification_follows" name="notification_follows" type="checkbox" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ auth()->user()->notification_follows ? 'checked' : '' }}>
                                    <label for="notification_follows" class="mr-2 block text-sm text-gray-700 dark:text-gray-300">
                                        إشعارات المتابعين الجدد
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- إعدادات الخصوصية -->
                        <div class="mt-6">
                            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">إعدادات الخصوصية</h3>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="privacy_profile" name="privacy_profile" type="checkbox" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ auth()->user()->privacy_profile ? 'checked' : '' }}>
                                    <label for="privacy_profile" class="mr-2 block text-sm text-gray-700 dark:text-gray-300">
                                        جعل الملف الشخصي عام
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="privacy_quotes" name="privacy_quotes" type="checkbox" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ auth()->user()->privacy_quotes ? 'checked' : '' }}>
                                    <label for="privacy_quotes" class="mr-2 block text-sm text-gray-700 dark:text-gray-300">
                                        عرض اقتباساتي للجميع
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- زر الحفظ -->
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                حفظ الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>