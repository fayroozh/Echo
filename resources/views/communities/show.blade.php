<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
                {{ $community->name }}
            </h2>
            <div class="flex space-x-2 space-x-reverse">
                @if(auth()->check())
                    @if($community->isOwner(auth()->user()))
                        <a href="{{ route('communities.dashboard', $community) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('لوحة التحكم') }}
                        </a>
                    @elseif($community->isMember(auth()->user()))
                        <form method="POST" action="{{ route('communities.leave', $community) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('مغادرة المجتمع') }}
                            </button>
                        </form>
                    @elseif($community->hasPendingJoinRequest(auth()->user()))
                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                            {{ __('طلب الانضمام قيد المراجعة') }}
                        </button>
                    @else
                        <form method="POST" action="{{ route('communities.join', $community) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('انضمام للمجتمع') }}
                            </button>
                        </form>
                    @endif

                    @if($community->isFollower(auth()->user()))
                        <form method="POST" action="{{ route('communities.unfollow', $community) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('إلغاء المتابعة') }}
                            </button>
                        </form>
                    @elseif($community->hasPendingFollowRequest())
                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                            {{ __('طلب المتابعة قيد المراجعة') }}
                        </button>
                    @else
                        <form method="POST" action="{{ route('communities.follow', $community) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('متابعة') }}
                            </button>
                        </form>
                    @endif

                    @if(!$community->isOwner(auth()->user()) && $community->owner)
                        <a href="{{ route('inbox.start', $community->owner->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('مراسلة المالك') }}
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- معلومات المجتمع -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ $community->image ? Storage::url($community->image) : asset('images/default-community.jpg') }}" alt="{{ $community->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-0 right-0 p-6">
                        <h1 class="text-white text-3xl font-bold">{{ $community->name }}</h1>
                        <div class="flex items-center mt-2">
                            <span class="text-yellow-400 flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $community->rating_avg ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </span>
                            <span class="text-white text-sm mr-1">({{ $community->ratings_count }})</span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $community->is_private ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }} ml-2">
                                {{ $community->is_private ? __('مجتمع خاص') : __('مجتمع عام') }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $community->category->name }}
                            </span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center ml-4">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $community->members_count }} {{ __('عضو') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $community->followers_count }} {{ __('متابع') }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('وصف المجتمع') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $community->description }}</p>
                    </div>

                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('فكرة المجتمع') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $community->idea }}</p>
                    </div>

                    <div class="mb-6 text-right">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('مالك المجتمع') }}</h3>
                        @if($community->owner)
                            <div class="flex items-center">
                                <img src="{{ $community->owner->profile_image_url }}" alt="{{ $community->owner->name }}" class="w-10 h-10 rounded-full ml-3">
                                <div>
                                    <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $community->owner->name }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('تاريخ الإنشاء') }}: {{ $community->created_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">{{ __('لا يوجد مالك حالياً') }}</p>
                        @endif
                    </div>

                    @if(auth()->check() && !$community->is_private || auth()->check() && $community->isFollower(auth()->user()))
                        <div class="mb-6 text-right">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('تقييم المجتمع') }}</h3>
                            @if(auth()->check() && !$community->hasRatedBy(auth()->user()))
                                <form method="POST" action="{{ route('communities.rate', $community) }}" class="mb-4">
                                    @csrf
                                    <div class="flex items-center">
                                        <div class="flex items-center ml-4">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" class="hidden peer" required>
                                                <label for="rating-{{ $i }}" class="cursor-pointer text-2xl text-gray-300 dark:text-gray-600 peer-checked:text-yellow-400 hover:text-yellow-400">
                                                    ★
                                                </label>
                                            @endfor
                                        </div>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            {{ __('تقييم') }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ __('لقد قمت بتقييم هذا المجتمع بالفعل.') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- المنشورات -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('منشورات المجتمع') }}</h3>
                        @if(auth()->check() && $community->isMember(auth()->user()))
                            <a href="{{ route('communities.posts.create', $community) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('إنشاء منشور جديد') }}
                            </a>
                        @endif
                        <div class="mt-6 flex justify-between items-center">
                            <div>
                                @if(auth()->id() === $community->user_id)
                                    <a href="{{ route('communities.edit', $community) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 ml-3">
                                        {{ __('تعديل المجتمع') }}
                                    </a>
                                    
                                    <button onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        {{ __('حذف المجتمع') }}
                                    </button>
                                    
                                    <form id="delete-form" action="{{ route('communities.destroy', $community) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                            
                            <!-- ... existing buttons ... -->
                        </div>
                    </div>

                    @if(!empty($posts) && count($posts) > 0)
                        <div class="space-y-6">
                            @foreach($posts as $post)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center">
                                            <img src="{{ $post->user->profile_image_url }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full ml-3">
                                            <div>
                                                <p class="text-gray-900 dark:text-gray-100 font-medium">{{ $post->user->name }}</p>
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-4 text-right">
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $post->title }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $post->content }}</p>
                                        @if($post->image)
                                            <div class="mt-3">
                                                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="rounded-lg max-h-96 mx-auto">
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- التعليقات -->
                                    <div class="mt-4 border-t border-gray-200 dark:border-gray-600 pt-4">
                                        <h5 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2 text-right">{{ __('التعليقات') }} ({{ $post->comments_count }})</h5>
                                        
                                        @if(count($post->comments) > 0)
                                            <div class="space-y-3">
                                                @foreach($post->comments as $comment)
                                                    <div class="bg-white dark:bg-gray-800 rounded p-3">
                                                        <div class="flex items-center mb-2">
                                                            <img src="{{ $comment->user->profile_image_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full ml-2">
                                                            <div>
                                                                <p class="text-gray-900 dark:text-gray-100 font-medium text-sm">{{ $comment->user->name }}</p>
                                                                <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $comment->created_at->diffForHumans() }}</p>
                                                            </div>
                                                        </div>
                                                        <p class="text-gray-600 dark:text-gray-400 text-right">{{ $comment->content }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400 text-center">{{ __('لا توجد تعليقات بعد.') }}</p>
                                        @endif
                                        
                                        @if(auth()->check() && $community->isMember(auth()->user()))
                                            <form method="POST" action="{{ route('communities.comments.store', $post) }}" class="mt-3">
                                                @csrf
                                                <div class="flex">
                                                    <textarea name="content" rows="2" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-right" placeholder="{{ __('أضف تعليقاً...') }}" required></textarea>
                                                    <button type="submit" class="mr-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        {{ __('تعليق') }}
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">{{ __('لا توجد منشورات في هذا المجتمع بعد.') }}</p>
                            @if(auth()->check() && $community->isMember(auth()->user()))
                                <a href="{{ route('communities.posts.create', $community) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                    {{ __('كن أول من ينشر') }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('{{ __("هل أنت متأكد من رغبتك في حذف هذا المجتمع؟ لا يمكن التراجع عن هذا الإجراء.") }}')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
