<x-app-layout>
    <div class="flex flex-col h-screen">
        @php
            // تحديد المستخدم الآخر في المحادثة
            $otherUser = auth()->id() == $conversation->user_one_id ? $conversation->userTwo : $conversation->userOne;
        @endphp

        <!-- رأس المحادثة مع معلومات المستخدم الآخر -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-4">
            <div class="flex items-center">
                <a href="{{ route('user.profile', $otherUser->id) }}" class="flex items-center">
                    <img src="{{ $otherUser->profile_image ? asset('storage/' . $otherUser->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                         alt="{{ $otherUser->name }}" 
                         class="w-10 h-10 rounded-full ml-3">
                    <div>
                        <h4 class="font-medium">{{ $otherUser->name }}</h4>
                        <div class="flex items-center">
                            <span id="user-status-indicator" class="w-2 h-2 rounded-full mr-2"></span>
                            <span id="user-status-text" class="text-sm text-gray-600 dark:text-gray-400"></span>
                        </div>
                        <div id="typing-indicator" class="text-sm text-gray-600 dark:text-gray-400 mt-1 hidden">
                            يكتب الآن...
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- صندوق عرض الرسائل -->
        <div class="flex-1 overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-4">
            <div id="messages-container" class="h-full overflow-y-auto p-2">
                @foreach($conversation->messages as $message)
                    <div class="{{ $message->sender_id == auth()->id() ? 'text-left ml-12' : 'text-right mr-12' }} mb-4">
                        <div class="inline-block p-3 rounded-lg {{ $message->sender_id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }}">
                            @if($message->attachment)
                                @if(\Illuminate\Support\Str::endsWith($message->attachment, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ asset('storage/' . $message->attachment) }}" alt="مرفق" class="max-w-full rounded mb-2" style="max-width: 200px;">
                                @else
                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="text-blue-400 underline block mb-2">
                                        📎 تحميل المرفق
                                    </a>
                                @endif
                            @endif
                            <p>{{ $message->body }}</p>
                            <div class="text-xs {{ $message->sender_id == auth()->id() ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400' }} mt-1">
                                {{ $message->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- نموذج إرسال رسالة جديدة -->
        <div class="sticky bottom-0 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <form id="message-form" action="{{ route('conversations.messages.store', $conversation->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center">
                    <input type="text" name="body" id="message-input" class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 ml-2" placeholder="اكتب رسالتك..." required>
                    <label for="attachment" class="cursor-pointer bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg px-4 py-2 ml-2">
                        📎
                        <input type="file" name="attachment" id="attachment" class="hidden" accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    </label>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-4 py-2">إرسال</button>
                </div>
                <div id="attachment-preview" class="mt-2 hidden">
                    <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded p-2">
                        <span id="attachment-name" class="text-sm text-gray-700 dark:text-gray-300 flex-1"></span>
                        <button type="button" id="remove-attachment" class="text-red-500 hover:text-red-700">
                            ✕
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conversationId = {{ $conversation->id }};
            const otherUserId = {{ $otherUser->id }};
            let isTyping = false;
            let typingTimeout;

            // تحديث حالة المستخدم بناءً على البيانات
            function updateUserStatus(isOnline) {
                const indicator = document.getElementById('user-status-indicator');
                const text = document.getElementById('user-status-text');
                
                if (isOnline) {
                    indicator.classList.remove('bg-gray-400');
                    indicator.classList.add('bg-green-500');
                    text.textContent = 'متصل الآن';
                } else {
                    indicator.classList.remove('bg-green-500');
                    indicator.classList.add('bg-gray-400');
                    text.textContent = 'غير متصل';
                }
            }

            // تحديث حالة الكتابة
            function updateTypingStatus(isTyping) {
                const typingIndicator = document.getElementById('typing-indicator');
                if (isTyping) {
                    typingIndicator.classList.remove('hidden');
                } else {
                    typingIndicator.classList.add('hidden');
                }
            }

            // استماع لحالة المستخدم عبر Echo
            window.Echo.channel('user-status')
                .listen('user-online-status', (e) => {
                    if (e.user_id === otherUserId) {
                        updateUserStatus(e.is_online);
                    }
                });

            // استماع لحالة الكتابة عبر Echo
            window.Echo.private(`conversation.${conversationId}`)
                .listen('user-typing', (e) => {
                    if (e.user_id === otherUserId) {
                        updateTypingStatus(e.is_typing);
                    }
                });

            // إرسال حالة الكتابة إلى السيرفر
            const messageInput = document.getElementById('message-input');
            messageInput.addEventListener('input', function() {
                if (!isTyping) {
                    isTyping = true;
                    sendTypingStatus(true);
                }
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(() => {
                    isTyping = false;
                    sendTypingStatus(false);
                }, 3000);
            });

            function sendTypingStatus(isTyping) {
                fetch(`/conversations/${conversationId}/typing`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ is_typing: isTyping })
                });
            }

            // معاينة المرفقات
            const attachmentInput = document.getElementById('attachment');
            const attachmentPreview = document.getElementById('attachment-preview');
            const attachmentName = document.getElementById('attachment-name');
            const removeAttachment = document.getElementById('remove-attachment');

            attachmentInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    attachmentName.textContent = this.files[0].name;
                    attachmentPreview.classList.remove('hidden');
                } else {
                    attachmentPreview.classList.add('hidden');
                }
            });

            removeAttachment.addEventListener('click', function() {
                attachmentInput.value = '';
                attachmentPreview.classList.add('hidden');
            });

            // تمرير لأسفل لعرض آخر رسالة
            const messagesContainer = document.getElementById('messages-container');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // جلب الحالة الأولية للمستخدم الآخر
            fetch(`/api/users/${otherUserId}/status`)
                .then(response => response.json())
                .then(data => {
                    updateUserStatus(data.is_online);
                });
        });
    </script>
    @endpush
</x-app-layout>
