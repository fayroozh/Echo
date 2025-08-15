<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ $user->id === Auth::id() ? 'المتابعون لي' : 'المتابعون لـ ' . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">المتابعون</h3>
                    <div class="flex space-x-4 space-x-reverse">
                        <a href="{{ route('follows.followers', $user->id) }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 pb-1">المتابعون ({{ $user->followers->count() }})</a>
                        <a href="{{ route('follows.following', $user->id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 pb-1">يتابع ({{ $user->following->count() }})</a>
                    </div>
                </div>

                @if($followers->count() > 0)
                    <div class="space-y-4">
                        @foreach($followers as $follow)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700">
                                            @if($follow->follower->profile_image)
                                                <img src="{{ asset('storage/' . $follow->follower->profile_image) }}" alt="{{ $follow->follower->name }}" class="h-full w-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($follow->follower->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $follow->follower->name }}" class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                        <div class="mr-4">
                                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ $follow->follower->name }}</h4>
                                            @if($follow->follower->bio)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-md">{{ $follow->follower->bio }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if(Auth::id() != $follow->follower->id)
                                        @php
                                            $isFollowing = Auth::user()->following()->where('following_id', $follow->follower->id)->exists();
                                        @endphp
                                        <form action="{{ route('follows.toggle', $follow->follower->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 text-sm rounded-md {{ $isFollowing ? 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200' : 'bg-blue-600 text-white' }} hover:opacity-90 transition-all duration-200">
                                                {{ $isFollowing ? 'إلغاء المتابعة' : 'متابعة' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $followers->links() }}
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">لا يوجد متابعون حاليًا</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>