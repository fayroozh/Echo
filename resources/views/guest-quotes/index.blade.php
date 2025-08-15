<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">💭 مشاعر الناس</h2>
                        <a href="{{ route('guest-quotes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            شارك مشاعرك
                        </a>
                    </div>

                    @if($guestQuotes->count() > 0)
                        <div class="space-y-6">
                            @foreach($guestQuotes as $quote)
                                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <p class="text-lg text-gray-800 dark:text-gray-200 mb-2">{{ $quote->content }}</p>
                                            @if($quote->feeling)
                                                <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm px-3 py-1 rounded-full">
                                                    {{ $quote->feeling }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $quote->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    <!-- قسم التعليقات -->
                                    <div class="mt-4 border-t pt-4">
                                        <h4 class="font-semibold mb-3">التعليقات والدعم:</h4>
                                        
                                        @auth
                                            <form action="{{ route('guest-quotes.comment', $quote) }}" method="POST" class="mb-4">
                                                @csrf
                                                <div class="flex gap-2">
                                                    <input type="text" name="comment" placeholder="اكتب تعليق مشجع أو نصيحة..." 
                                                           class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700" required>
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">
                                                        إرسال
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">سجل دخولك</a> لتتمكن من التعليق والدعم
                                            </p>
                                        @endauth
                                        
                                        <!-- عرض التعليقات -->
                                        @if($quote->comments && $quote->comments->count() > 0)
                                            <div class="space-y-3">
                                                @foreach($quote->comments as $comment)
                                                    <div class="bg-white dark:bg-gray-600 p-3 rounded">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <span class="font-semibold text-sm">{{ $comment->user->name }}</span>
                                                                <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $comment->content }}</p>
                                                            </div>
                                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">لا توجد تعليقات بعد. كن أول من يقدم الدعم!</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $guestQuotes->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400 text-lg mb-4">لا توجد مشاعر مشاركة بعد</p>
                            <a href="{{ route('guest-quotes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                كن أول من يشارك
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>