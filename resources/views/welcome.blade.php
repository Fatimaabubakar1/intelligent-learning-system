<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LingNaija</title>
        {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @include('nav')

        <div class="hero">
            <h1 class="hero-text">Your Smart Companion for Language Mastery – Learn, Practice, Connect!</h1>
            <button class="btn-start"><a href="{{ route('register') }}" class="Start-btn-a">Start Learning</a></button>
        </div>

        @include('footer')
    </body>
</html>

