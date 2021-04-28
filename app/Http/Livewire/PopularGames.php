<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class PopularGames extends Component
{
    public $popularGames = [];

    public function loadPopularGames()
    {
        $before = Carbon::now()->subMonths(6)->timestamp;
        $after = Carbon::now()->addMonths(6)->timestamp;

        $this->popularGames = Cache::remember('popular-games', 60, function () use($before, $after) {
            return $this->popularGames = Http::withHeaders(config('services.igdb'))
                                        ->withBody(
                                        'fields name, rating, cover.url, platforms.abbreviation;
                                        where(first_release_date > '.$before.'& first_release_date < '.$after.');
                                        where rating != null;
                                        sort rating desc;
                                        limit 12;'
                                        ,'text/plain')
                                        ->post('https://api.igdb.com/v4/games')
                                        ->json();
        });


    }

    public function render()
    {
        return view('livewire.popular-games');
    }
}
