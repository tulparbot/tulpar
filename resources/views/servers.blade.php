<x-layout>
    <div class="flex space-x-3">
        @foreach($servers as $server)
            <div class="shadow bg-gray-800 rounded border border-gray-700 py-3 px-2 w-1/3">
                @if (mb_strlen($server->icon) > 0)
                    <img src="{{ $server->extra->icon }}" alt="{{ $server->name }}"
                         class="w-32 h-32 mt-4 mb-5 rounded-full mx-auto bg-white">
                @else
                    <div
                        class="w-32 h-32 mx-auto mt-4 mb-5 rounded-full bg-white flex text-center align-middle text-black select-none">
                        <div class="mx-auto my-auto font-semibold text-3xl">{{ $server->name }}</div>
                    </div>
                @endif
                <div class="text-xl w-full text-center my-3">{{ $server->name }}</div>
                <p>{{ $server?->model?->description ?? '' }}</p>
                <div class="w-full flex space-x-3 pt-3">
                    @if ($server->joinned)
                        <a href="{{ route('servers.dashboard', $server->model->id) }}"
                           class="inline-flex leading-6 w-1/3 transition bg-indigo-900 hover:bg-indigo-800 rounded py-2 px-3 shadow">
                            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                                 fill="currentColor">
                                <defs></defs>
                                <title>dashboard</title>
                                <rect x="24" y="21" width="2" height="5"></rect>
                                <rect x="20" y="16" width="2" height="10"></rect>
                                <path d="M11,26a5.0059,5.0059,0,0,1-5-5H8a3,3,0,1,0,3-3V16a5,5,0,0,1,0,10Z"></path>
                                <path
                                    d="M28,2H4A2.002,2.002,0,0,0,2,4V28a2.0023,2.0023,0,0,0,2,2H28a2.0027,2.0027,0,0,0,2-2V4A2.0023,2.0023,0,0,0,28,2Zm0,9H14V4H28ZM12,4v7H4V4ZM4,28V13H28.0007l.0013,15Z"></path>
                                <rect data-name="<Transparent Rectangle>" fill="none"></rect>
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                        <a href="#"
                           class="inline-flex leading-6 w-1/3 transition bg-indigo-900 hover:bg-indigo-800 rounded py-2 px-3">
                            <svg class="w-6 h-6 mr-2" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                 x="0px" y="0px"
                                 viewBox="0 0 16 16" xml:space="preserve" fill="currentColor"><title>settings</title>
                                <path
                                    d="M13.5,8.4c0-0.1,0-0.3,0-0.4c0-0.1,0-0.3,0-0.4l1-0.8c0.4-0.3,0.4-0.9,0.2-1.3l-1.2-2C13.3,3.2,13,3,12.6,3c-0.1,0-0.2,0-0.3,0.1l-1.2,0.4c-0.2-0.1-0.4-0.3-0.7-0.4l-0.3-1.3C10.1,1.3,9.7,1,9.2,1H6.8c-0.5,0-0.9,0.3-1,0.8L5.6,3.1C5.3,3.2,5.1,3.3,4.9,3.4L3.7,3C3.6,3,3.5,3,3.4,3C3,3,2.7,3.2,2.5,3.5l-1.2,2C1.1,5.9,1.2,6.4,1.6,6.8l0.9,0.9c0,0.1,0,0.3,0,0.4c0,0.1,0,0.3,0,0.4L1.6,9.2c-0.4,0.3-0.5,0.9-0.2,1.3l1.2,2C2.7,12.8,3,13,3.4,13c0.1,0,0.2,0,0.3-0.1l1.2-0.4c0.2,0.1,0.4,0.3,0.7,0.4l0.3,1.3c0.1,0.5,0.5,0.8,1,0.8h2.4c0.5,0,0.9-0.3,1-0.8l0.3-1.3c0.2-0.1,0.4-0.2,0.7-0.4l1.2,0.4c0.1,0,0.2,0.1,0.3,0.1c0.4,0,0.7-0.2,0.9-0.5l1.1-2c0.2-0.4,0.2-0.9-0.2-1.3L13.5,8.4z M12.6,12l-1.7-0.6c-0.4,0.3-0.9,0.6-1.4,0.8L9.2,14H6.8l-0.4-1.8c-0.5-0.2-0.9-0.5-1.4-0.8L3.4,12l-1.2-2l1.4-1.2c-0.1-0.5-0.1-1.1,0-1.6L2.2,6l1.2-2l1.7,0.6C5.5,4.2,6,4,6.5,3.8L6.8,2h2.4l0.4,1.8c0.5,0.2,0.9,0.5,1.4,0.8L12.6,4l1.2,2l-1.4,1.2c0.1,0.5,0.1,1.1,0,1.6l1.4,1.2L12.6,12z"></path>
                                <path
                                    d="M8,11c-1.7,0-3-1.3-3-3s1.3-3,3-3s3,1.3,3,3C11,9.6,9.7,11,8,11C8,11,8,11,8,11z M8,6C6.9,6,6,6.8,6,7.9C6,7.9,6,8,6,8c0,1.1,0.8,2,1.9,2c0,0,0.1,0,0.1,0c1.1,0,2-0.8,2-1.9c0,0,0-0.1,0-0.1C10,6.9,9.2,6,8,6C8.1,6,8,6,8,6z"></path>
                                <rect fill="none" width="16" height="16"></rect></svg>
                            {{ __('Settings') }}
                        </a>
                        <a href="#"
                           class="transition font-semibold text-yellow-300 hover:text-gray-50 inline-flex leading-6 w-1/3 bg-yellow-900 hover:bg-yellow-800 rounded py-2 px-3">
                            <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"
                                 fill="currentColor">
                                <path
                                    d="M238.72754,73.53516a15.90452,15.90452,0,0,0-16.70508-2.29981l-50.584,22.48242L141.98633,40.70312a15.999,15.999,0,0,0-27.97266,0L84.56055,93.7168,33.96875,71.23145A16.00031,16.00031,0,0,0,11.89551,89.51172l25.44531,108.333a15.83567,15.83567,0,0,0,7.4082,10.09179,16.15491,16.15491,0,0,0,12.49317,1.65137,265.89708,265.89708,0,0,1,141.46875-.01367,16.15321,16.15321,0,0,0,12.4873-1.65137,15.83531,15.83531,0,0,0,7.40821-10.084L244.0957,89.52051A15.90513,15.90513,0,0,0,238.72754,73.53516ZM202.98535,194.15625a281.68183,281.68183,0,0,0-150.06543.042l-.00293-.00976v-.001L27.47168,85.85254,78.0625,108.33789a15.9219,15.9219,0,0,0,20.48535-6.85059L128,48.47266l29.45312,53.0166a15.92186,15.92186,0,0,0,20.48438,6.84863l50.584-22.48144Zm-35.0293-31.64063a7.9897,7.9897,0,0,1-8.793,7.11915,298.37472,298.37472,0,0,0-62.32618,0,8,8,0,0,1-1.67382-15.91211,314.358,314.358,0,0,1,65.67382,0A7.99917,7.99917,0,0,1,167.95605,162.51562Z"></path>
                            </svg>
                            {{ __('Premium') }}
                        </a>
                    @else
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-layout>
