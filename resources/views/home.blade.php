<x-layout>
    <div class="w-full flex py-20">
        <div class="w-full lg:w-1/2">
            <div class="text-6xl font-semibold tracking-tighter space-y-2">
                <div>Build the best</div>
                <div>Discord Server!</div>
            </div>
            <div class="py-10 text-gray-500 font-semibold">
                Configure moderation, leveling, Twitch alerts, and much more with the most easy-to-use dashboard!
            </div>
            <hr class="border-gray-600 w-1/3">
            <div class="py-10 text-sm text-gray-500 font-semibold space-y-3">
                @foreach(['Moderation', 'Custom commands', 'Reaction roles', 'Twitch, YouTube and Twitter alerts'] as $text)
                    <div class="flex h-6 items-center space-x-2">
                        <svg class="w-4 h-4 fill-current text-green-400" viewBox="0 0 15 15" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M14.707 3L5.5 12.207.293 7 1 6.293l4.5 4.5 8.5-8.5.707.707z"
                                  fill="currentColor"></path>
                        </svg>
                        <span>{{ $text }}</span>
                    </div>
                @endforeach
            </div>
            <div class="py-10 flex space-x-4">
                <a href="#"
                   class="bg-indigo-800 hover:bg-indigo-700 inline-block py-4 px-5 font-normal rounded-md transition">
                    Add to Discord
                </a>
                <a href="#"
                   class="bg-gray-800 hover:bg-gray-700 inline-block py-4 px-5 font-normal rounded-md transition">
                    See features
                </a>
            </div>
        </div>
        <div class="w-full flex lg:w-1/2 hidden lg:block text-center lg:text-right px-4">
            <div class="flex h-full items-center">
                <img
                    class="w-full"
                    src="{{ asset(config('tulpar.type') == \App\Enums\VersionType::Release ? 'img/mascot/brand/mascot.svg' : 'img/branding/mascot/nightly/mascot.svg') }}"
                    alt="{{ config('app.name') }}">
            </div>
        </div>
    </div>
</x-layout>
