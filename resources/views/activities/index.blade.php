<x-app-layout>
    <div class="container mx-auto p-4 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-center">أنشطتي</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- قسم التعليقات على اقتباساتي -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    التعليقات على اقتباساتي
                </h2>
                
                @if($comments->count() > 0)
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @foreach($comments as $comment)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-800 dark:text-gray-200 mb-2">{{ $comment->comment }}</p>
                                <div class="text-sm text-gray-700 dark:text-gray-600">
                                    <p>على اقتباسك: "{{ Str::limit($comment->quote->content, 50) }}"</p>
                                    <p>{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-700 dark:text-gray-600 text-center py-8">لا يوجد تعليقات على اقتباساتك حتى الآن.</p>
                @endif
            </div>
            
            <!-- قسم الإعجابات التي قمت بها -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                    إعجاباتي
                </h2>
                
                @if($likes->count() > 0)
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @foreach($likes as $like)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-800 dark:text-gray-200 mb-2">"{{ Str::limit($like->quote->content, 100) }}"</p>
                                <div class="text-sm text-gray-700 dark:text-gray-600 flex justify-between items-center">
                                    <span>بواسطة: {{ $like->quote->user->name }}</span>
                                    <span>{{ $like->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-700 dark:text-gray-600 text-center py-8">لم تقم بالإعجاب بأي اقتباس حتى الآن.</p>
                @endif
            </div>
            <!-- إضافة قسم جديد للتفاعلات -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mt-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    تفاعلاتي
                </h2>
                
                @if($reactions->count() > 0)
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @foreach($reactions as $reaction)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-800 dark:text-gray-200 mb-2">"{{ Str::limit($reaction->quote->content, 100) }}"</p>
                                <div class="text-sm text-gray-700 dark:text-gray-600 flex justify-between items-center">
                                    <span>{{ $reaction->type }} على اقتباس {{ $reaction->quote->user->name }}</span>
                                    <span>{{ $reaction->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-700 dark:text-gray-600 text-center py-8">لم تقم بأي تفاعلات حتى الآن.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>