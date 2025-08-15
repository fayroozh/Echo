<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('الإشعارات') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">الإشعارات</h3>
                    @if($notifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                تحديد الكل كمقروء
                            </button>
                        </form>
                    @endif
                </div>

                @if($notifications->count() > 0)
                    <div class="space-y-4">
                        @foreach($notifications as $notification)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 {{ $notification->read ? 'opacity-70' : '' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            @if(!$notification->read)
                                                <span class="h-2 w-2 bg-blue-600 rounded-full mr-2"></span>
                                            @endif
                                            <p class="text-gray-800 dark:text-gray-200 {{ $notification->read ? 'text-gray-500 dark:text-gray-400' : 'font-semibold' }}">
                                                {{ $notification->content }}
                                            </p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2 space-x-reverse">
                                        @if(!$notification->read)
                                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                    تحديد كمقروء
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">لا توجد إشعارات حاليًا</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>