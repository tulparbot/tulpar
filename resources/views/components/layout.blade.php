<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name', 'Tulpar') }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="antialiased cursor-default">
<div class="min-h-screen bg-gray-900 text-gray-50">
    <header class="bg-gray-900 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex w-full">
                <div class="text-lg font-semibold">
                    <a href="{{ route('home') }}">
                        <img
                            class="h-14"
                            src="{{ asset(config('tulpar.type') == \App\Enums\VersionType::Release ? 'img/branding/brand/brand-invert.svg' : 'img/branding/brand/nightly/brand-invert.svg') }}"
                            alt="{{ config('app.name') }}">
                    </a>
                </div>
                <ul class="ml-auto space-x-2 text-sm my-auto h-8 inline-flex">
                    <li>
                        <a href="#" class="transition text-gray-300 hover:text-gray-50 leading-8">
                            {{ __('Moderation') }}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="transition text-gray-300 hover:text-gray-50 leading-8">
                            {{ __('Leveling') }}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="transition text-gray-300 hover:text-gray-50 leading-8">
                            {{ __('Support Server') }}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="transition text-gray-300 hover:text-gray-50 leading-8">
                            {{ __('Tutorials') }}
                        </a>
                    </li>
                    <li>
                        <x-premium-link class="leading-8"/>
                    </li>
                    @guest()
                        <li>
                            <a href="{{ route('login') }}"
                               class="transition text-indigo-50 bg-indigo-800 hover:bg-indigo-700 py-2 px-5 rounded-md shadow font-semibold leading-8">
                                {{ __('Login') }}
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('logout') }}"
                               class="transition text-gray-300 hover:text-gray-50 leading-8">
                                {{ __('Logout') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @auth()
            <div class="mb-4 bg-gray-800 shadow border border-gray-700 py-3 px-4 rounded space-x-4 flex align-middle">
                <a href="{{ route('servers') }}">My Servers</a>
                <x-premium-link text="My Nodes"/>
                <a href="#">My Top List</a>
                <a href="#">Global Top List</a>
            </div>
        @endif
        <main>
            {{ $slot }}
        </main>
    </div>

    <footer class="bg-gray-800 py-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex w-full">
                <div class="w-full lg:w-1/2 space-y-6">
                    <div>
                        <img
                            class="h-14"
                            src="{{ asset(config('tulpar.type') == \App\Enums\VersionType::Release ? 'img/branding/brand/brand-invert.svg' : 'img/branding/brand/nightly/brand-invert.svg') }}"
                            alt="{{ config('app.name') }}">
                    </div>
                    <div class="space-y-1.5 text-gray-400 font-semibold text-sm">
                        <div>
                            The best Discord bot to bootstrap and grow your Discord server
                        </div>
                        <div>
                            Copyright &copy; {{ date('Y') }} İsa Eken / Hostadresim
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 space-y-6">
                    <div>
                        
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
