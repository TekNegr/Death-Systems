<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Portfolio') }}</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireScripts

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    @livewireStyles
</head>
<body class="bg-gray-900 text-white h-screen w-screen overflow-hidden">

    <!-- Desktop Component -->
    {{-- <main class="w-full h-full"> --}}
    <main >
        {{-- @livewire('desktop') --}}
        @livewire('desktop')
    </main>


</body>
</html>