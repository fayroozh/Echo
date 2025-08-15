<!-- في قسم عرض الاقتباسات -->
@foreach($quotes as $quote)
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md relative">
        <!-- قائمة الخيارات -->
        <div class="absolute top-4 left-4" x-data="{ open: false }">
            <button @click="open = !open" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                </svg>
            </button>
            
            <div x-show="open" @click.away="open = false" x-transition 
                 class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-10">
                <a href="{{ route('quotes.edit', $quote) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                    ✏️ تعديل
                </a>
                <form action="{{ route('quotes.destroy', $quote) }}" method="POST" class="block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('هل أنت متأكد من حذف هذا الاقتباس؟')"
                            class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                        🗑️ حذف
                    </button>
                </form>
            </div>
        </div>
        
        <!-- محتوى الاقتباس -->
        <p class="text-lg text-gray-800 dark:text-gray-200 mb-4 pr-8">{{ $quote->content }}</p>
        <!-- ... باقي محتوى الاقتباس -->
    </div>
@endforeach