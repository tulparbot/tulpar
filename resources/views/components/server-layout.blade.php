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
                    <a href="{{ route('home') }}">
                        <img
                            class="h-14"
                            src="{{ asset(config('tulpar.type') == \App\Enums\VersionType::Release ? 'img/branding/brand/brand-invert.svg' : 'img/branding/brand/nightly/brand-invert.svg') }}"
                            alt="{{ config('app.name') }}">
                    </a>
                </div>
                <ul class="ml-auto space-x-2 text-sm my-auto h-8 inline-flex">
                    <li>
                        <a href="#"
                           class="transition text-gray-300 hover:text-gray-50 leading-8 bg-gray-700 py-2 px-3 font-semibold rounded shadow">
                            {{ __('Support Server') }}
                        </a>
                    </li>
                    <li>
                        <a href="#"
                           class="transition text-gray-300 hover:text-gray-50 leading-8 bg-gray-700 py-2 px-3 font-semibold rounded shadow">
                            {{ __('Tutorials') }}
                        </a>
                    </li>
                    <li>
                        <a href="#"
                           class="transition pl-6 font-semibold text-yellow-300 hover:text-gray-50 relative inline-block align-middle pl-9 pr-3 leading-8 rounded shadow bg-yellow-900 hover:bg-yellow-800">
                            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"
                                     fill="currentColor">
                                    <path
                                        d="M238.72754,73.53516a15.90452,15.90452,0,0,0-16.70508-2.29981l-50.584,22.48242L141.98633,40.70312a15.999,15.999,0,0,0-27.97266,0L84.56055,93.7168,33.96875,71.23145A16.00031,16.00031,0,0,0,11.89551,89.51172l25.44531,108.333a15.83567,15.83567,0,0,0,7.4082,10.09179,16.15491,16.15491,0,0,0,12.49317,1.65137,265.89708,265.89708,0,0,1,141.46875-.01367,16.15321,16.15321,0,0,0,12.4873-1.65137,15.83531,15.83531,0,0,0,7.40821-10.084L244.0957,89.52051A15.90513,15.90513,0,0,0,238.72754,73.53516ZM202.98535,194.15625a281.68183,281.68183,0,0,0-150.06543.042l-.00293-.00976v-.001L27.47168,85.85254,78.0625,108.33789a15.9219,15.9219,0,0,0,20.48535-6.85059L128,48.47266l29.45312,53.0166a15.92186,15.92186,0,0,0,20.48438,6.84863l50.584-22.48144Zm-35.0293-31.64063a7.9897,7.9897,0,0,1-8.793,7.11915,298.37472,298.37472,0,0,0-62.32618,0,8,8,0,0,1-1.67382-15.91211,314.358,314.358,0,0,1,65.67382,0A7.99917,7.99917,0,0,1,167.95605,162.51562Z"></path>
                                </svg>
                            </div>
                            <span>{{ $text ?? __('Premium') }}</span>
                        </a>
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
                               class="transition text-gray-300 hover:text-gray-50 leading-8 bg-gray-700 py-2 px-3 font-semibold rounded shadow">
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
            <div class="w-24">
                <div class="space-y-3 rounded-3xl bg-gray-700 py-2" style="min-height: 42rem">
                    @foreach($_own_servers as $_own_server)
                        @if ($_own_server->model != null)
                            <a href="{{ route('servers.dashboard', $_own_server->model) }}" class="block">
                                <div class="relative group">
                                    <div
                                        class="hidden group-hover:block absolute left-full ml-2 top-1/2 transform -translate-y-1/2">
                                        <div
                                            class="bg-black rounded z-50 w-40 bg-opacity-80 text-sm font-semibold text-center py-2 px-2">
                                            {{ $_own_server->name }}
                                        </div>
                                    </div>
                                    @if (mb_strlen($_own_server->extra->icon) > 0)
                                        <img src="{{ $_own_server->extra->icon }}" alt="{{ $_own_server->name }}"
                                             class="w-14 h-14 rounded-3xl mx-auto bg-white hover:rounded-full transition">
                                    @else
                                        <div
                                            class="w-14 h-14 mx-auto rounded-3xl bg-white hover:rounded-full transition flex text-center align-middle text-black select-none">
                                            <div
                                                class="mx-auto my-auto font-semibold text-3xl">{{ $_own_server->short_name }}</div>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endif
                    @endforeach
                    <a href="#" class="block">
                        <div class="relative group">
                            <div
                                class="hidden group-hover:block absolute left-full ml-2 top-1/2 transform -translate-y-1/2">
                                <div
                                    class="bg-black rounded z-50 w-40 bg-opacity-80 text-sm font-semibold text-center py-2 px-2">
                                    Add a another server
                                </div>
                            </div>
                            <div
                                class="w-14 h-14 mx-auto rounded-3xl bg-white hover:bg-gray-200 hover:rounded-full transition-all flex text-center align-middle text-black select-none">
                                <div
                                    class="mx-auto my-auto font-semibold text-3xl">
                                    +
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="w-full">
                <div class="border-b border-gray-900 pb-6">
                    @if (mb_strlen($server?->data?->extra?->icon ?? $server->icon) > 0)
                        <img src="{{ $server?->data?->extra?->icon ?? $server->icon }}" alt="{{ $server->name }}"
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
