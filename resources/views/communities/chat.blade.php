<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <a href="{{ route('communities.show', $community) }}" class="text-blue-600 hover:text-blue-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">محادثة {{ $community->name }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $community->members_count ?? 0 }} عضو</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $community->is_private ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                            {{ $community->is_private ? 'خاص' : 'عام' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Chat Container -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <!-- Messages Area -->
                <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50 dark:bg-gray-900">
                    @forelse($messages as $message)
                        <div class="flex items-start space-x-3 space-x-reverse">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full" 
                                     src="{{ $message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=3B82F6&color=fff' }}" 
                                     alt="{{ $message->user->name }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $message->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $message->message }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">لا توجد رسائل</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">كن أول من يبدأ المحادثة في هذا المجتمع.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                    <form id="message-form" action="{{ route('communities.chat.store', $community) }}" method="POST" class="flex space-x-2 space-x-reverse">
                        @csrf
                        <div class="flex-1">
                            <input type="text" 
                                   name="message" 
                                   id="message-input"
                                   placeholder="اكتب رسالتك هنا..." 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                   required
                                   maxlength="1000">
                        </div>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Chat Guidelines -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">إرشادات المحادثة</h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <ul class="list-disc list-inside space-y-1">
                                <li>كن محترماً ومهذباً مع جميع الأعضاء</li>
                                <li>تجنب المحتوى المسيء أو غير المناسب</li>
                                <li>ابق في موضوع المجتمع</li>
                                <li>لا تشارك معلومات شخصية حساسة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('messages-container');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        
        // Auto-scroll to bottom
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        // Initial scroll to bottom
        scrollToBottom();
        
        // Handle form submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageText = messageInput.value.trim();
            
            if (!messageText) return;
            
            // Disable input while sending
            messageInput.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add message to chat
                    const messageHtml = `
                        <div class="flex items-start space-x-3 space-x-reverse">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full" 
                                     src="${data.message.user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(data.message.user.name) + '&background=3B82F6&color=fff'}" 
                                     alt="${data.message.user.name}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${data.message.user.name}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">الآن</p>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">${data.message.message}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    messageInput.value = '';
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ في إرسال الرسالة');
            })
            .finally(() => {
                messageInput.disabled = false;
                messageInput.focus();
            });
        });
        
        // Auto-refresh messages every 5 seconds
        setInterval(function() {
            const lastMessageId = messagesContainer.querySelector('[data-message-id]:last-child')?.dataset.messageId || 0;
            
            fetch(`{{ route('communities.chat.new-messages', $community) }}?last_message_id=${lastMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            const messageHtml = `
                                <div class="flex items-start space-x-3 space-x-reverse" data-message-id="${message.id}">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full" 
                                             src="${message.user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(message.user.name) + '&background=3B82F6&color=fff'}" 
                                             alt="${message.user.name}">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${message.user.name}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">${new Date(message.created_at).toLocaleTimeString('ar-SA')}</p>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">${message.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                        });
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error fetching new messages:', error));
        }, 5000);
        
        // Focus on input
        messageInput.focus();
    });
    </script>
    @endpush
</x-app-layout>

