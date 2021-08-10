<x-server-layout :server="$server">
    <div class="bg-gray-800 shadow-lg rounded space-y-5 py-4 px-3">
        @foreach($responses as $response)
            <div class="space-y-2 bg-gray-700 rounded shadow py-3 px-2">
                <div class="w-full flex">
                    <div class="w-full">{{ $response->message }}</div>
                    <div><a href="{{ route('servers.auto-responders.destroy', [$server, $response]) }}"
                            class="text-red-400 font-semibold text-sm">Delete</a></div>
                </div>
                <div class="w-full space-y-1.5">
                    <div class="w-full flex py-1.5 px-1.5 rounded items-center">
                        <div class="w-40 text-gray-400 font-semibold text-sm">Message</div>
                        <div class="w-full">{{ $response->message }}</div>
                    </div>
                    <div class="w-full flex py-1.5 px-1.5 rounded items-center">
                        <div class="w-40 text-gray-400 font-semibold text-sm">Reply</div>
                        <div class="w-full">{{ $response->reply }}</div>
                    </div>
                    <div class="w-full flex py-1.5 px-1.5 rounded items-center">
                        <div class="w-40 text-gray-400 font-semibold text-sm">Emoji</div>
                        <div class="w-full">{{ $response->emoji }}</div>
                    </div>
                </div>
            </div>
        @endforeach
        <div>
            <form action="{{ route('servers.auto-responders', $server) }}" method="post">
                @csrf
                <div class="space-y-2 bg-gray-700 rounded shadow py-3 px-2">
                    <div class="text-right text-sm text-gray-300 font-semibold pr-2">
                        Create New Auto Response Rule
                    </div>
                    <div>
                        <label for="message" class="hidden">Message</label>
                        <input type="text" id="message" name="message" placeholder="Message"
                               class="w-full bg-gray-900 shadow text-sm py-1.5 px-2 rounded">
                    </div>
                    <div>
                        <label for="reply" class="hidden">Reply</label>
                        <input type="text" id="reply" name="reply" placeholder="Reply"
                               class="w-full bg-gray-900 shadow text-sm py-1.5 px-2 rounded">
                    </div>
                    <div class="flex">
                        <label for="emoji" class="hidden">Emoji</label>
                        <input type="text" id="emoji" name="emoji" placeholder="Emoji" readonly
                               class="w-full mr-0.5 bg-gray-900 shadow py-1.5 px-2 rounded">
                        <button id="emoji-trigger" type="button"
                                class="w-40 mr-0.5 text-sm text-center bg-gray-900 shadow py-1.5 px-2 rounded">
                            Select Emoji
                        </button>
                        <button type="button" onclick="document.getElementById('emoji').value = ''"
                                class="w-40 text-sm text-center bg-gray-900 shadow py-1.5 px-2 rounded">
                            Clear Emoji
                        </button>
                    </div>
                    <div>
                        <button type="submit"
                                class="w-full py-2 px-3 text-center bg-gray-900 text-sm cursor-pointer rounded shadow font-semibold text-gray-300">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-server-layout>
