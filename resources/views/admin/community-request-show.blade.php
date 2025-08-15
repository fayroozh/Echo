<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('تفاصيل طلب إنشاء مجتمع') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-semibold mb-2">{{ __('معلومات المجتمع') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('اسم المجتمع:') }}</p>
                                <p class="font-medium">{{ $community->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('التصنيف:') }}</p>
                                <p class="font-medium">{{ $community->category ? $community->category->name : __('بدون تصنيف') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('الخصوصية:') }}</p>
                                <p class="font-medium">{{ $community->is_private ? __('خاص') : __('عام') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('تاريخ الطلب:') }}</p>
                                <p class="font-medium">{{ $community->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-semibold mb-2">{{ __('وصف المجتمع') }}</h3>
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                            <p>{{ $community->description }}</p>
                        </div>
                    </div>

                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-semibold mb-2">{{ __('فكرة المجتمع') }}</h3>
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                            <p>{{ $community->idea }}</p>
                        </div>
                    </div>

                    @if($community->image)
                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-semibold mb-2">{{ __('صورة المجتمع') }}</h3>
                        <div class="mt-2">
                            <img src="{{ Storage::url($community->image) }}" alt="{{ $community->name }}" class="max-w-full h-auto rounded-lg max-h-64">
                        </div>
                    </div>
                    @endif

                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-semibold mb-2">{{ __('معلومات المستخدم') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('اسم المستخدم:') }}</p>
                                <p class="font-medium">{{ $community->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('البريد الإلكتروني:') }}</p>
                                <p class="font-medium">{{ $community->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 space-x-reverse mt-6">
                        <form method="POST" action="{{ route('admin.community-requests.reject', $community->id) }}">
                            @csrf
                            @method('PUT')
                            <x-danger-button type="submit">
                                {{ __('رفض الطلب') }}
                            </x-danger-button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.community-requests.approve', $community->id) }}">
                            @csrf
                            @method('PUT')
                            <x-primary-button type="submit">
                                {{ __('قبول الطلب') }}
                            </x-primary-button>
                        </form>
                        
                        <a href="{{ route('admin.community-requests.index') }}">
                            <x-secondary-button>
                                {{ __('العودة للقائمة') }}
                            </x-secondary-button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>