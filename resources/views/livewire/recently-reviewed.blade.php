<div wire:init="loadRecentlyReviewed" class="recently-reviewed-container space-y-12 mt-8">
    @forelse ($recentlyReviewed as $review)
        <div class="game bg-gray-800 rounded-lg shadow-md flex px-6 py-6">
            <div class="relative flex-none">
                <a href="#">
                    <img src="{{ isset($review['cover']) ? Str::replaceFirst('thumb', 'cover_big', $review['cover']['url']) : '#' }}" alt="game cover" class="w-48 hover:opacity-75 transition ease-in-out duration-150">
                </a>
                @if (isset($review['rating']))
                    <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-900 rounded-full" style="right: -20px; bottom: -20px">
                        <div class="font-semibold text-xs flex justify-center items-center h-full">
                            {{ round($review['rating']).'%' }}
                        </div>
                    </div>
                @endif
            </div>
            <div class="ml-6 lg:ml-12">
                <a href="#" class="block text-lg font-semibold leading-tight hover:text-gray-400 mt-4">{{$review['name']}}</a>
                <div class="text-gray-400 mt-1">
                    @foreach ($review['platforms'] as $rvplatform)
                        @if (array_key_exists('abbreviation', $rvplatform))
                            {{ $rvplatform['abbreviation'] }},
                        @endif
                    @endforeach
                </div>
                <p class="mt-6 text-gray-400 hidden lg:block">
                    {{$review['summary']}}
                </p>
            </div>
        </div> <!-- end game -->
    @empty
       @foreach(range(1,3) as $game)
            <div class="game bg-gray-800 rounded-lg shadow-md flex px-6 py-6">
                <div class="relative flex-none">
                    <div class="bg-gray-700 w-32 lg:w-48 h-40 lg:h-56"></div>
                </div>
                <div class="ml-6 lg:ml-12">
                    <div class="inline-block text-transparent bg-gray-700 rounded text-lg font-semibold leading-tight mt-4">Title goes here</div>
                    <div class="mt-8 space-y-4 hidden lg:block">
                        <span class="text-transparent bg-gray-700 rounded inline-block">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
                        <span class="text-transparent bg-gray-700 rounded inline-block">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
                        <span class="text-transparent bg-gray-700 rounded inline-block">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</span>
                    </div>
                </div>
            </div> <!-- end game -->
        @endforeach
    @endforelse
</div>
