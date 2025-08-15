<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">الاقتباس الأصلي</h2>
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <p class="text-lg">{{ $quote->content }}</p>
                    @if ($quote->feeling)
                        <p class="text-sm text-gray-600 mt-2">الشعور: {{ $quote->feeling }}</p>
                    @endif
                </div>

                <h3 class="text-xl font-bold mb-4">اقتباسات مشابهة</h3>
                @if ($similarQuotes->count() > 0)
                    <div class="space-y-4">
                        @foreach ($similarQuotes as $similarQuote)
                            <div class="p-4 border rounded-lg hover:bg-gray-50">
                                <p>{{ $similarQuote->content }}</p>
                                @if ($similarQuote->feeling)
                                    <p class="text-sm text-gray-600 mt-2">الشعور: {{ $similarQuote->feeling }}</p>
                                @endif
                                <div class="mt-2 text-sm text-gray-500">
                                    <a href="{{ route('quotes.show', $similarQuote) }}" class="text-blue-600 hover:underline">عرض الاقتباس</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">لم يتم العثور على اقتباسات مشابهة.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>