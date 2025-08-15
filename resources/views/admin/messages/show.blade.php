<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('عرض الرسالة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <a href="{{ route('admin.messages.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                            &larr; العودة إلى قائمة الرسائل
                        </a>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $adminMessage->subject }}</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $adminMessage->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">من: {{ $adminMessage->user->name }}</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                            <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $adminMessage->message }}</p>
                        </div>
                    </div>

                    @if(!$adminMessage->is_read)
                    <form method="POST" action="{{ route('admin-messages.mark-as-read', $adminMessage->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            تحديد كمقروءة
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>