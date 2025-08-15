<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- رأس الصفحة مع زر العودة -->
            <div class="flex justify-between items-center mb-8">
                <a href="{{ route('welcome') }}" class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-all duration-300">
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    العودة للرئيسية
                </a>
                <a href="{{ route('guest-quotes.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-300">
                    عرض مشاعر الآخرين
                </a>
            </div>
            
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    اكتب مشاعرك، وخلّي العالم يسمع الصدى
                </h1>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-2">
                    مساحة آمنة ومجهولة للتعبير عن مشاعرك وأفكارك
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    لا حاجة للتسجيل • مجهول تماماً • آمن ومحمي
                </p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-6 py-4 rounded-lg relative mb-6 flex items-center">
                            <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('guest-quotes.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <!-- Content Field -->
                        <div>
                            <label for="content" class="block text-gray-800 dark:text-gray-200 text-lg font-semibold mb-3">
                                <svg class="w-5 h-5 inline ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                </svg>
                                شاركنا مشاعرك
                            </label>
                            <textarea
                                name="content"
                                id="content"
                                rows="6"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-lg leading-relaxed transition-all duration-200"
                                placeholder="اكتب هنا ما يجول في خاطرك... لا تتردد، هذا مكانك الآمن للتعبير"
                            ></textarea>
                        </div>

                        <!-- Feeling Field -->
                        <div>
                            <label for="feeling" class="block text-gray-800 dark:text-gray-200 text-lg font-semibold mb-3">
                                <svg class="w-5 h-5 inline ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                كيف تشعر؟ (اختياري)
                            </label>
                            <input
                                type="text"
                                name="feeling"
                                id="feeling"
                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-lg transition-all duration-200"
                                placeholder="مثال: حزين، سعيد، متفائل، قلق..."
                            >
                        </div>

                        <!-- Privacy Notice -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="text-blue-800 dark:text-blue-300 font-semibold mb-1">خصوصيتك مهمة لنا</h4>
                                    <p class="text-blue-700 dark:text-blue-400 text-sm">
                                        مشاركتك مجهولة تماماً ولن نحفظ أي معلومات شخصية عنك. سيتم مراجعة المحتوى قبل النشر للتأكد من سلامة المجتمع.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center pt-4">
                            <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <svg class="w-5 h-5 inline ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                </svg>
                                أرسل صداك للعالم
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center mt-8">
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    كل صدى يحمل قصة، وكل قصة تستحق أن تُسمع
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
