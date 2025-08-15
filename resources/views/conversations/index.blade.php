@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('المحادثات') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- قائمة المحادثات -->
                <div class="md:w-2/3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">📥 رسائلك</h2>
                            
                            @if($conversations->isEmpty())
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    لا توجد محادثات بعد. ابدأ محادثة جديدة مع أحد المستخدمين.
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($conversations as $conversation)
                                        @php
                                            $otherUser = $conversation->user_one_id == $userId ? $conversation->userTwo : $conversation->userOne;
                                            $lastMessage = $conversation->messages->last();
                                        @endphp
                                        <a href="{{ route('conversations.show', $conversation->id) }}" class="block">
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150">
                                                <div class="flex items-center">
                                                    <img src="{{ $otherUser->profile_image ? asset('storage/' . $otherUser->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                                                         alt="{{ $otherUser->name }}" 
                                                         class="w-12 h-12 rounded-full ml-4">
                                                    <div class="flex-1">
                                                        <div class="flex items-center">
                                                            <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ $otherUser->name }}</h3>
                                                            <div class="flex items-center mr-2">
                                                                <span class="user-status-indicator w-2 h-2 rounded-full mr-1" 
                                                                      data-user-id="{{ $otherUser->id }}"></span>
                                                            </div>
                                                        </div>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                                            @if($lastMessage)
                                                                @if($lastMessage->attachment)
                                                                    📎 مرفق
                                                                @else
                                                                    {{ Str::limit($lastMessage->body, 50) }}
                                                                @endif
                                                            @else
                                                                بدء محادثة جديدة
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- قائمة المستخدمين المتصلين -->
                <div class="md:w-1/3">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">المتصلين الآن</h2>
                            
                            <div id="online-users-container" class="space-y-4">
                                <!-- سيتم تحديث هذا القسم ديناميًا بواسطة JavaScript -->
                                <div class="text-center text-gray-500 dark:text-gray-400 py-8">جاري تحميل المستخدمين المتصلين...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث حالة المستخدمين عند تحميل الصفحة
            updateAllUserStatuses();
            
            // الاستماع لتغييرات حالة المستخدمين
            window.Echo.channel('user-status')
                .listen('user-online-status', (e) => {
                    updateUserStatus(e.user_id, e.is_online);
                });
            
            function updateAllUserStatuses() {
                const indicators = document.querySelectorAll('.user-status-indicator');
                indicators.forEach(indicator => {
                    const userId = indicator.dataset.userId;
                    fetch(`/api/users/${userId}/status`)
                        .then(response => response.json())
                        .then(data => {
                            updateUserStatusUI(indicator, data.is_online);
                        });
                });
            }
            
            function updateUserStatus(userId, isOnline) {
                const indicators = document.querySelectorAll(`.user-status-indicator[data-user-id="${userId}"]`);
                indicators.forEach(indicator => {
                    updateUserStatusUI(indicator, isOnline);
                });
            }
            
            function updateUserStatusUI(indicator, isOnline) {
                if (isOnline) {
                    indicator.classList.remove('bg-gray-400');
                    indicator.classList.add('bg-green-500');
                } else {
                    indicator.classList.remove('bg-green-500');
                    indicator.classList.add('bg-gray-400');
                }
            }
            
            // تحديث قائمة المستخدمين المتصلين
            fetchOnlineUsers();
            
            // تحديث القائمة كل 30 ثانية
            setInterval(fetchOnlineUsers, 30000);
            
            function fetchOnlineUsers() {
                fetch('/api/users/online')
                    .then(response => response.json())
                    .then(data => {
                        updateOnlineUsersList(data.users);
                        updateOnlineUsersCount(data.users.length);
                    })
                    .catch(error => console.error('Error fetching online users:', error));
            }
            
            function updateOnlineUsersList(users) {
                const container = document.getElementById('online-users-container');
                
                if (users.length === 0) {
                    container.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">لا يوجد مستخدمين متصلين حاليًا</div>';
                    return;
                }
                
                let html = '';
                users.forEach(user => {
                    html += `
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex items-center">
                        <img src="${user.profile_image ? '/storage/' + user.profile_image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&color=7F9CF5&background=EBF4FF'}" 
                             alt="${user.name}" 
                             class="w-10 h-10 rounded-full ml-3">
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-200">${user.name}</h3>
                            <div class="flex items-center mt-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                <span class="text-xs text-gray-600 dark:text-gray-400">متصل الآن</span>
                            </div>
                            <a href="/inbox/start/${user.id}" class="mt-1 inline-block text-xs text-blue-600 dark:text-blue-400 hover:underline">إرسال رسالة</a>
                        </div>
                    </div>
                    `;
                });
                
                container.innerHTML = html;
            }
            
            function updateOnlineUsersCount(count) {
                const countElements = document.querySelectorAll('.online-users-count');
                countElements.forEach(el => {
                    el.textContent = count;
                    el.style.display = count > 0 ? 'inline-block' : 'none';
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
