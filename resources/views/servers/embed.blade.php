<x-server-layout :server="$server">
    <div class="bg-gray-800 shadow-lg rounded space-y-5 py-4 px-3">
        <div>
            <label for="name" class="block w-full ml-0.5 mb-1 text-xs font-semibold text-gray-400 uppercase">
                Embed Name
            </label>
            <input required type="text" id="name" name="name" placeholder="Embed Name"
                   class="w-full rounded py-2 px-3 text-gray-200 bg-gray-900">
        </div>

        <div>
            <label for="channel" class="block w-full ml-0.5 mb-1 text-xs font-semibold text-gray-400 uppercase">
                Post In Channel
            </label>
            <select required name="channel" id="channel" class="w-full rounded py-2 px-3 text-gray-200 bg-gray-900">
                @foreach ($server->channels as $channel)
                    <option value="{{ $channel->id }}">
                        {{ $channel->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="rounded border-l-4 border-blue-500 bg-gray-700 p-3">
            <input type="hidden" name="author_image">
            <input type="hidden" name="thumbnail">

            <div class="w-full space-x-1 flex">
                <div class="w-full">
                    <div class="space-y-0.5">
                        <div class="w-full">
                            <label for="author_name" class="hidden">Author Name</label>
                            <input type="text" name="author_name" id="author_name" placeholder="Author Name"
                                   class="bg-gray-800 text-sm py-1 px-2 w-full">
                        </div>

                        <div class="w-full">
                            <label for="title_text" class="hidden">Title Text</label>
                            <input type="text" name="title_text" id="title_text" placeholder="Title Text"
                                   class="bg-gray-800 text-sm py-1 px-2 w-full">
                        </div>
                    </div>

                    <div class="w-full my-1">
                        <label for="content" class="hidden">Content</label>
                        <textarea name="content" id="content" cols="30" rows="10" placeholder="Content"
                                  class="bg-gray-800 text-sm py-1 px-2 w-full"></textarea>
                    </div>

                    <div class="w-full my-2 space-y-2">
                        <div class="w-full flex">
                            <div class="w-full">
                                <div>
                                    <label for="field_name[]" class="hidden">Field name</label>
                                    <input type="text" id="field_name[]" placeholder="Field name"
                                           class="bg-gray-800 text-sm py-1 px-2 w-full">
                                </div>
                                <div>
                                    <label for="field_value[]" class="hidden">Field value</label>
                                    <input type="text" id="field_value[]" placeholder="Field value"
                                           class="bg-gray-800 text-sm py-1 px-2 w-full">
                                </div>
                            </div>
                            <div class="w-32">
                                <a href="#">remove</a>
                                <a href="#">toggle inline</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-28">
                    <div class="block bg-gray-800 w-28 h-28"></div>
                </div>
            </div>
        </div>

        <div>
            <label for="description" class="block w-full ml-0.5 mb-1 text-xs font-semibold text-gray-400 uppercase">Embed
                Name</label>
            <input required type="text" id="description" name="description" placeholder="Embed Name"
                   class="w-full rounded py-2 px-3 text-gray-200 bg-gray-900">
        </div>


        <input type="text" name="field_name[]">
        <input type="text" name="field_value[]">
        <input type="number" value="0" name="field_inline[]">

        <input type="text" name="footer">

        <input type="color" name="color">
    </div>
</x-server-layout>
