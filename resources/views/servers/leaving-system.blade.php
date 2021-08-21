<x-server-layout :server="$server">
    <div class="w-full flex space-x-2">
        <div class="w-full lg:w-2/3">
            <div class="bg-gray-800 shadow-lg rounded py-4 px-3">
                <form action="" method="post" class="space-y-5">
                    <div class="flex w-full select-none">
                        <label for="enabled" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" id="enabled" class="sr-only">
                                <div class="block bg-gray-600 w-14 h-8 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                            </div>
                            <div class="ml-3 text-gray-200 font-medium">
                                Enabled
                            </div>
                        </label>
                    </div>

                    <div>
                        <label for="text"
                               class="block w-full ml-0.5 mb-1 text-xs font-semibold text-gray-400 uppercase">
                            Leave Text
                        </label>
                        <input required type="text" id="text" name="text" placeholder="Leave Text" value="Bye"
                               class="w-full rounded py-2 px-3 text-gray-200 bg-gray-900">
                    </div>

                    <div>
                        <label for="background"
                               class="block w-full ml-0.5 mb-1 text-xs font-semibold text-gray-400 uppercase">
                            Background Image URL
                        </label>
                        <input type="url" id="background" name="background" placeholder="Background Image URL"
                               class="w-full rounded py-2 px-3 text-gray-200 bg-gray-900">
                    </div>

                    <div>
                        <label for="foreground"
                               class="block w-full ml-0.5 mb-1 text-xs font-semibold text-gray-400 uppercase">
                            Foreground Color
                        </label>
                        <input type="color" id="foreground" name="foreground" placeholder="Foreground Color"
                               value="#000000"
                               class="w-full rounded py-2 px-3 text-gray-200 bg-gray-900">
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="block bg-gray-700 hover:bg-gray-600 rounded p-2 w-full">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="w-full lg:w-1/3">
            <a href="#" onclick="
                document.getElementById('preview').src =
                '{{ route('servers.leaving-system.preview', $server) }}?time=' + Date.now().valueOf() +
                '&text='+ encodeURI(document.getElementById('text').value)+
                '&background='+ encodeURI(document.getElementById('background').value) +
                '&foreground='+ encodeURI(document.getElementById('foreground').value.replace('#', ''))
                ;
                "
               class="bg-gray-800 hover:bg-gray-700 rounded text-gray-100 py-2 px-2 block text-sm text-center"
            >Re-Create Preview</a>

            <div class="mt-2">
                <img id="preview" src="{{ route('servers.leaving-system.preview', $server) }}" alt="Preview"
                     class="w-full">
            </div>
        </div>
    </div>
</x-server-layout>
