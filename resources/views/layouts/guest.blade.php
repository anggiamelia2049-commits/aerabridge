<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AERA Bridge') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
            input:-webkit-autofill {
                -webkit-box-shadow: 0 0 0 1000px #F8FFFF inset;
            }
        </style>
    </head>
    <body class="text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-[#0C343D] relative overflow-hidden px-4 py-10">

            <!-- Garis elips tipis -->
            <div class="absolute left-1/2 top-1/2 w-[900px] h-[330px] -translate-x-1/2 -translate-y-1/2 rotate-[25deg] rounded-full border border-white/80 hidden md:block"></div>

            <!-- Lingkaran abu-abu -->
            <div class="absolute left-[17%] top-[30%] w-24 h-24 rounded-full bg-[#9FABAE] hidden md:block"></div>
            <div class="absolute left-[77%] top-[62%] w-24 h-24 rounded-full bg-[#9FABAE] hidden md:block"></div>

            <!-- Bentuk kiri -->
            <div class="absolute -left-10 bottom-[-10%] w-44 h-[75%] bg-[#76A5AF] rotate-[33deg] rounded-3xl hidden md:block"></div>
            <div class="absolute -left-24 top-[38%] w-72 h-56 bg-[#A9D6DD] rotate-[35deg] rounded-3xl hidden md:block"></div>

            <!-- Bentuk kanan -->
            <div class="absolute right-0 top-0 w-36 h-[52%] bg-[#76A5AF] hidden md:block"></div>
            <div class="absolute -right-6 top-[48%] w-64 h-44 bg-[#A9D6DD] -rotate-[55deg] rounded-3xl hidden md:block"></div>
            <div class="absolute -right-10 top-[58%] w-72 h-44 bg-[#45818E] -rotate-[55deg] rounded-3xl hidden md:block"></div>

            <!-- Card login -->
            <div class="relative z-10 w-full sm:max-w-md bg-[#F8FFFF] shadow-2xl rounded-2xl px-6 py-9">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>