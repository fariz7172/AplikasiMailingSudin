<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
        <style>
            body {
                background: radial-gradient(circle at top right, #3b82f6 0%, #1e40af 100%);
                min-height: 100vh;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased selection:bg-blue-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center p-6">
            <div class="w-full sm:max-w-md">
                <div class="flex flex-col items-center mb-8">
                    <img src="{{ asset('assets/logo.png') }}" class="w-24 h-auto drop-shadow-2xl mb-4" alt="Logo Jakarta">
                    <h1 class="text-white text-2xl font-extrabold text-center tracking-tight leading-tight">
                        Aplikasi SPP & SPM
                    </h1>
                    <p class="text-blue-100 text-sm font-medium mt-1 text-center">
                        Sudin SDA Kota Administrasi Jakarta Utara
                    </p>
                </div>

                <div class="w-full glass-card shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-3xl overflow-hidden p-8">
                    {{ $slot }}
                </div>

                <div class="mt-8 text-center">
                    <p class="text-blue-200 text-xs font-bold uppercase tracking-widest opacity-80">
                        &copy; 2026 Suku Dinas Sumber Daya Air Jakarta Utara
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
