<x-app-layout>
    <div class="max-w-3xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">اقتباسات عن: {{ $feeling }}</h1>

        @forelse ($quotes as $quote)
            <div class="mb-4 p-4 border rounded bg-white shadow">
                <p class="text-lg">{{ $quote->content }}</p>
                <p class="text-sm text-gray-500 mt-2">شعور: {{ $quote->feeling }}</p>
            </div>
        @empty
            <p class="text-gray-500">لا يوجد اقتباسات بعد بهذا الشعور.</p>
        @endforelse

        <div class="mt-4">
            {{ $quotes->links() }}
        </div>
    </div>
</x-app-layout>
