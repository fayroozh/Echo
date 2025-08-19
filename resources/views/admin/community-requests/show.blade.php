<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">إدارة المجتمعات</h1>
                    
                    <!-- طلبات إنشاء المجتمعات الجديدة -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-4">طلبات إنشاء مجتمعات جديدة</h2>
                        
                        @if($pendingCommunities->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100 dark:bg-gray-800">
                                        <tr>
                                            <th class="py-3 px-4 text-right">اسم المجتمع</th>
                                            <th class="py-3 px-4 text-right">الوصف</th>
                                            <th class="py-3 px-4 text-right">المؤسس</th>
                                            <th class="py-3 px-4 text-right">تاريخ الطلب</th>
                                            <th class="py-3 px-4 text-right">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingCommunities as $community)
                                            <tr class="border-b dark:border-gray-600">
                                                <td class="py-3 px-4">{{ $community->name }}</td>
                                                <td class="py-3 px-4">{{ Str::limit($community->description, 50) }}</td>
                                                <td class="py-3 px-4">{{ $community->owner ? $community->owner->name : 'غير محدد' }}</td>
                                                <td class="py-3 px-4">{{ $community->created_at->format('Y-m-d') }}</td>
                                                <td class="py-3 px-4">
                                                    <div class="flex space-x-2 space-x-reverse">
                                                        <form action="{{ route('admin.communities.approve', $community->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-md text-sm">
                                                                موافقة
                                                            </button>
                                                        </form>
                                                        
                                                        <form action="{{ route('admin.communities.reject', $community->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md text-sm">
                                                                رفض
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">لا توجد طلبات إنشاء مجتمعات جديدة.</p>
                        @endif
                    </div>
                    
                    <!-- المجتمعات النشطة -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">المجتمعات النشطة</h2>
                        
                        @if($activeCommunities->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($activeCommunities as $community)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-4">
                                        <div class="flex items-center mb-3">
                                            @if($community->image)
                                                <img src="{{ asset('storage/' . $community->image) }}" alt="{{ $community->name }}" class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                                                    {{ substr($community->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div class="mr-3">
                                                <h3 class="font-bold">{{ $community->name }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $community->members_count }} عضو</p>
                                            </div>
                                        </div>
                                        
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">{{ Str::limit($community->description, 100) }}</p>
                                        
                                        <div class="flex justify-between mt-4">
                                            <a href="{{ route('communities.show', $community->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                                عرض المجتمع
                                            </a>
                                            
                                            <form action="{{ route('admin.communities.disable', $community->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                                    تعطيل المجتمع
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">لا توجد مجتمعات نشطة.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>