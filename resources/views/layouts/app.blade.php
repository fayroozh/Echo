<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="$watch('darkMode', val => {
          localStorage.setItem('darkMode', val)
          if (val) {
              document.documentElement.classList.add('dark')
          } else {
              document.documentElement.classList.remove('dark')
          }
       });
       if (darkMode) {
          document.documentElement.classList.add('dark')
       }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Echo | صدى') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* أنماط إزاحة المحتوى عند فتح الشريط الجانبي */
        body {
            transition: padding-right 0.3s ease-in-out;
        }

        body.sidebar-open {
            padding-right: 16rem;
            /* 64px = 16rem */
        }

        /* تعديل للشاشات الصغيرة */
        @media (max-width: 1024px) {
            body.sidebar-open {
                padding-right: 0;
            }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow mt-16">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow mt-16 pt-4">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="mt-auto py-6 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                <p>© {{ date('Y') }} Echo | صدى - منصة الاقتباسات. جميع الحقوق محفوظة.</p>
            </div>
        </footer>
    </div>

    <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>

    <script>
        const beamsClient = new PusherPushNotifications.Client({
            instanceId: '185e33be-a71f-488d-a930-b5d781729e19', // هذا من حسابك
        });

        beamsClient.start()
            .then(() => beamsClient.addDeviceInterest('hello'))
            .then(() => console.log('✅ Successfully registered and subscribed!'))
            .catch(console.error);
    </script>

</body>

</html>