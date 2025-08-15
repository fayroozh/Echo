<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('صندوق الرسائل') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(count($conversations) > 0)
                        <div class="space-y-4">
                            @foreach($conversations as $conversation)
                                <a href="{{ route('chat.show', $conversation->id) }}" class="block">
                                    <div class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <img src="{{ $conversation->otherUser->profile_image ? '/storage/' . $conversation->otherUser->profile_image : 'https://ui-avatars.com/api/?name=' . urlencode($conversation->otherUser->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                             alt="{{ $conversation->otherUser->name }}" 
                                             class="w-12 h-12 rounded-full ml-4">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-center">
                                                <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ $conversation->otherUser->name }}</h3>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $conversation->lastMessage ? $conversation->lastMessage->created_at->diffForHumans() : '' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center mt-1">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                                    {{ $conversation->lastMessage ? $conversation->lastMessage->content : 'لا توجد رسائل بعد' }}
                                                </p>
                                                @if($conversation->unreadCount > 0)
                                                    <span class="bg-blue-500 text-white text-xs rounded-full px-2 py-1 mr-2">
                                                        {{ $conversation->unreadCount }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center mt-1">
                                                @if($conversation->otherUser->isOnline())
                                                    <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">متصل الآن</span>
                                                @else
                                                    <span class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full mr-2"></span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">غير متصل</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">لا توجد محادثات بعد</p>
                            <a href="{{ route('users.online') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                ابدأ محادثة جديدة
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>