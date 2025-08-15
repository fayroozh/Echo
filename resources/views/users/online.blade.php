<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-right">
            {{ __('المستخدمين المتصلين الآن') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="online-users-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- سيتم تحديث هذا القسم ديناميكيًا بواسطة JavaScript -->
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">جاري تحميل المستخدمين المتصلين...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث قائمة المستخدمين المتصلين عند تحميل الصفحة
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
                    container.innerHTML = '<div class="text-center col-span-full text-gray-500 dark:text-gray-400 py-8">لا يوجد مستخدمين متصلين حاليًا</div>';
                    return;
                }
                
                let html = '';
                users.forEach(user => {
                    html += `
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex items-center">
                        <img src="${user.profile_image ? '/storage/' + user.profile_image : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&color=7F9CF5&background=EBF4FF'}" 
                             alt="${user.name}" 
                             class="w-12 h-12 rounded-full ml-4">
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-gray-200">${user.name}</h3>
                            <div class="flex items-center mt-1">
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">متصل الآن</span>
                            </div>
                            <a href="/inbox/start/${user.id}" class="mt-2 inline-block text-sm text-blue-600 dark:text-blue-400 hover:underline">إرسال رسالة</a>
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