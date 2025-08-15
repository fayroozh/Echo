<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">تصدير الاقتباس كصورة</h1>
        
        <div id="quote-container" class="p-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-6">
            <div id="quote-image" class="quote-image p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                <p class="text-xl mb-4 text-gray-800 dark:text-gray-200">{{ $quote->content }}</p>
                <div class="flex items-center text-gray-600 dark:text-gray-600">
                    <span>{{ $quote->user->name }}</span>
                    @if($quote->feeling)
                        <span class="mx-2">|</span>
                        <span>#{{ $quote->feeling }}</span>
                    @endif
                </div>
                <div class="mt-4 text-sm text-gray-500 dark:text-gray-600">
                    Echo | صدى
                </div>
            </div>
        </div>
        
        <div class="flex space-x-4 space-x-reverse">
            <button id="download-image" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                تحميل الصورة
            </button>
            <button id="change-theme" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                تغيير المظهر
            </button>
            <a href="{{ route('quotes.show', $quote) }}" class="px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                العودة
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quoteImage = document.getElementById('quote-image');
            const downloadBtn = document.getElementById('download-image');
            const themeBtn = document.getElementById('change-theme');
            let currentTheme = 'light';
            
            // تغيير المظهر
            themeBtn.addEventListener('click', function() {
                if (currentTheme === 'light') {
                    quoteImage.classList.remove('bg-white', 'border-gray-200');
                    quoteImage.classList.add('bg-gray-800', 'border-gray-700');
                    quoteImage.querySelectorAll('p').forEach(p => {
                        p.classList.remove('text-gray-800');
                        p.classList.add('text-gray-200');
                    });
                    quoteImage.querySelectorAll('.text-gray-500, .text-gray-600').forEach(el => {
                        el.classList.remove('text-gray-500', 'text-gray-600');
                        el.classList.add('text-gray-400');
                    });
                    currentTheme = 'dark';
                } else {
                    quoteImage.classList.remove('bg-gray-800', 'border-gray-700');
                    quoteImage.classList.add('bg-white', 'border-gray-200');
                    quoteImage.querySelectorAll('p').forEach(p => {
                        p.classList.remove('text-gray-200');
                        p.classList.add('text-gray-800');
                    });
                    quoteImage.querySelectorAll('.text-gray-400').forEach(el => {
                        el.classList.remove('text-gray-400');
                        el.classList.add('text-gray-600');
                    });
                    currentTheme = 'light';
                }
            });
            
            // تحميل الصورة
            downloadBtn.addEventListener('click', function() {
                html2canvas(quoteImage, {
                    backgroundColor: currentTheme === 'light' ? '#ffffff' : '#1f2937',
                    scale: 2, // جودة أعلى
                    logging: false,
                    useCORS: true
                }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'اقتباس-{{ Str::slug($quote->id) }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });
        });
    </script>
</x-app-layout>