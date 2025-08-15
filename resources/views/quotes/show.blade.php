<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('عرض الاقتباس') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 p-4 border border-gray-200 dark:border-gray-700 rounded">
                        <div class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ $quote->user->name }} - {{ $quote->created_at->format('d/m/Y h:i A') }}
                        </div>
                        <div class="text-lg">
                            {{ $quote->content }}
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium mb-2">مشاركة الاقتباس</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-md font-medium mb-2">المحادثات الحالية</h4>
                                @if(count($conversations) > 0)
                                    <ul class="space-y-2">
                                        @foreach($conversations as $conversation)
                                            <li class="flex justify-between items-center p-2 border border-gray-200 dark:border-gray-700 rounded">
                                                <span>{{ $conversation->otherUser()->name }}</span>
                                                <button 
                                                    class="quote-button bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
                                                    data-quote-id="{{ $quote->id }}"
                                                    data-conversation-id="{{ $conversation->id }}">
                                                    مشاركة
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">لا توجد محادثات حالية</p>
                                @endif
                            </div>
                            
                            <div>
                                <h4 class="text-md font-medium mb-2">بدء محادثة جديدة</h4>
                                <form action="{{ route('quotes.share.new', $quote->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">اختر مستخدم</label>
                                        <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                            <option value="">اختر مستخدم</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">مشاركة في محادثة جديدة</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('quotes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">العودة للاقتباسات</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners to quote buttons
            document.querySelectorAll('.quote-button').forEach(button => {
                button.addEventListener('click', function() {
                    const quoteId = this.dataset.quoteId;
                    const conversationId = this.dataset.conversationId;
                    const button = this;
                    
                    button.disabled = true;
                    button.textContent = 'جاري المشاركة...';
                    
                    axios.post(`/quotes/${quoteId}/share`, {
                        conversation_id: conversationId
                    })
                    .then(response => {
                        if (response.data.success) {
                            button.textContent = 'تمت المشاركة';
                            button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                            button.classList.add('bg-green-500');
                            
                            // Show success message
                            const successMessage = document.createElement('div');
                            successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                            successMessage.textContent = 'تم مشاركة الاقتباس بنجاح';
                            document.body.appendChild(successMessage);
                            
                            setTimeout(() => {
                                successMessage.remove();
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error sharing quote:', error);
                        button.textContent = 'فشلت المشاركة';
                        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                        button.classList.add('bg-red-500');
                        
                        setTimeout(() => {
                            button.disabled = false;
                            button.textContent = 'مشاركة';
                            button.classList.remove('bg-red-500');
                            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
                        }, 3000);
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>