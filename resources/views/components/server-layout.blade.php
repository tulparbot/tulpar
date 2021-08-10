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
<body class="font-sans antialiased cursor-default">
<div class="min-h-screen bg-gray-900 text-gray-50">
    <header class="bg-gray-800 shadow">
        <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex w-full">
                <div class="text-lg font-semibold">
                    <a href="#">{{ config('app.name') }}</a>
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

    <div class="w-full flex">
        <div class="w-full max-w-sm bg-gray-800 py-2 px-3 flex space-x-3">
            <div class="w-24 space-y-4 rounded-3xl bg-gray-700 py-2">
                @foreach($_own_servers as $_own_server)
                    <a href="{{ route('servers.dashboard', $_own_server) }}">
                        <div class="relative group">
                            <div
                                class="hidden group-hover:block absolute left-full ml-2 top-1/2 transform -translate-y-1/2">
                                <div
                                    class="bg-black rounded z-50 w-40 bg-opacity-80 text-sm font-semibold text-center py-2 px-2">
                                    {{ $_own_server->name }}
                                </div>
                            </div>
                            @if (mb_strlen($_own_server->icon) > 0)
                                <img src="{{ $_own_server->icon }}" alt="{{ $_own_server->name }}"
                                     class="w-14 h-14 rounded-3xl mx-auto bg-white">
                            @else
                                <div
                                    class="w-14 h-14 mx-auto rounded-3xl bg-white flex text-center align-middle text-black select-none">
                                    <div
                                        class="mx-auto my-auto font-semibold text-3xl">{{ $_own_server->short_name }}</div>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="w-full">
                <div class="border-b border-gray-900 pb-6">
                    @if (mb_strlen($server->icon) > 0)
                        <img src="{{ $server->icon }}" alt="{{ $server->name }}"
                             class="w-24 h-24 rounded-full mx-auto bg-white">
                    @else
                        <div
                            class="w-24 h-24 mx-auto rounded-full bg-white flex text-center align-middle text-black select-none">
                            <div
                                class="mx-auto my-auto font-semibold text-3xl">{{ $server->short_name }}</div>
                        </div>
                    @endif

                    <div class="mt-4 text-center font-semibold text-lg">
                        {{ $server->name }}
                    </div>
                </div>
                @foreach ($nav_links as $nav_link_title => $nav_link)
                    <div class="py-5">
                        <div class="text-sm font-semibold text-gray-500 pb-2 mb-2">{{ __($nav_link_title) }}</div>
                        <div class="space-y-1">
                            @foreach($nav_link as $__link)
                                <a href="{{ $__link['href'] }}"
                                   class="{{ $__link['active'] ? 'block transition bg-gray-700 hover:bg-gray-600 py-2 px-1.5 rounded-xl' : 'block transition bg-gray-800 hover:bg-gray-700 py-2 px-1.5 rounded-xl' }}">
                                    <div class="flex align-middle h-8 leading-8 px-0.5 font-semibold">
                                        {!! $__link['svg'] !!}
                                        {{ __($__link['title']) }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="max-w-7xl w-full py-6 px-4 sm:px-6 lg:px-8">
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        </div>
    </footer>
</div>
</body>
</html>
