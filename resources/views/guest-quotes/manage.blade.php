<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-4">إدارة اقتراحات الزوار</h2>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2">المحتوى</th>
                                    <th class="px-4 py-2">الشعور</th>
                                    <th class="px-4 py-2">عنوان IP</th>
                                    <th class="px-4 py-2">تاريخ الإرسال</th>
                                    <th class="px-4 py-2">الحالة</th>
                                    <th class="px-4 py-2">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guestQuotes as $quote)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $quote->content }}</td>
                                        <td class="border px-4 py-2">{{ $quote->feeling }}</td>
                                        <td class="border px-4 py-2">{{ $quote->ip_address }}</td>
                                        <td class="border px-4 py-2">{{ $quote->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="border px-4 py-2">
                                            @if ($quote->status === 'pending')
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">قيد المراجعة</span>
                                            @elseif ($quote->status === 'approved')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">تمت الموافقة</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded">مرفوض</span>
                                            @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            @if ($quote->status === 'pending')
                                                <form method="POST" action="{{ route('guest-quotes.update-status', $quote) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded mr-2 hover:bg-green-600">
                                                        موافقة
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('guest-quotes.update-status', $quote) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                                        رفض
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $guestQuotes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>