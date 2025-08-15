<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('المحادثة مع') }} {{ $conversation->otherUser()->name }}
            <span id="online-status" class="text-sm text-gray-500 dark:text-gray-400 mr-2">
                @if($conversation->otherUser()->isOnline())
                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-1"></span> متصل الآن
                @else
                    <span class="inline-block w-3 h-3 bg-gray-500 rounded-full mr-1"></span> غير متصل
                @endif
            </span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="chat-messages" class="h-96 overflow-y-auto mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded" data-conversation="{{ $conversation->id }}">
                        @foreach($messages as $message)
                            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }} mb-4" id="message-{{ $message->id }}">
                                <div class="{{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }} rounded-lg px-4 py-2 max-w-[70%]">
                                    @if($message->quote_id)
                                        <div class="border-r-4 border-gray-400 dark:border-gray-500 pr-2 mb-2 italic text-sm">
                                            {{ $message->quote->content }}
                                        </div>
                                    @endif
                                    {{ $message->content }}
                                    <div class="text-xs mt-1 {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ $message->created_at->format('h:i A') }}
                                        @if($message->user_id === auth()->id())
                                            <span class="read-status mr-2">
                                                {{ $message->is_read ? 'تم القراءة' : 'تم الإرسال' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div id="typing-indicator" class="text-sm text-gray-500 dark:text-gray-400 italic mb-2" style="display: none;"></div>
                    
                    <form id="message-form" class="flex">
                        <input type="text" id="message-input" data-conversation="{{ $conversation->id }}" class="flex-1 rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" placeholder="اكتب رسالتك هنا...">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-md">إرسال</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatContainer = document.getElementById('chat-messages');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const conversationId = chatContainer.dataset.conversation;
            
            // Scroll to bottom of chat
            chatContainer.scrollTop = chatContainer.scrollHeight;
            
            // Listen for online status changes
            window.Echo.private(`user.${conversationId}`)
                .listen('.user.online', (e) => {
                    const statusElement = document.getElementById('online-status');
                    statusElement.innerHTML = '<span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-1"></span> متصل الآن';
                })
                .listen('.user.offline', (e) => {
                    const statusElement = document.getElementById('online-status');
                    statusElement.innerHTML = '<span class="inline-block w-3 h-3 bg-gray-500 rounded-full mr-1"></span> غير متصل';
                });
            
            // Send message
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const content = messageInput.value.trim();
                if (!content) return;
                
                axios.post(`/chat/${conversationId}/messages`, {
                    content: content
                })
                .then(response => {
                    // Clear input
                    messageInput.value = '';
                    
                    // Add message to chat
                    const messageHtml = createMessageElement(response.data.message);
                    chatContainer.insertAdjacentHTML('beforeend', messageHtml);
                    
                    // Scroll to bottom
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                });
            });
            
            function createMessageElement(message) {
                return `
                <div class="flex justify-end mb-4" id="message-${message.id}">
                    <div class="bg-blue-500 text-white rounded-lg px-4 py-2 max-w-[70%]">
                        ${message.content}
                        <div class="text-xs mt-1 text-blue-100">
                            ${new Date(message.created_at).toLocaleTimeString('ar-SA')}
                            <span class="read-status mr-2">تم الإرسال</span>
                        </div>
                    </div>
                </div>`;
            }
        });
    </script>
    @endpush
</x-app-layout>