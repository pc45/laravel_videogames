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
        <div>
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    @endforelse
</div>
