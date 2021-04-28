<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class PopularGames extends Component
{
    public $popularGames = [];

    public function loadPopularGames()
    {
        $before = Carbon::now()->subMonths(6)->timestamp;
        $after = Carbon::now()->addMonths(6)->timestamp;

        $popularGamesUnformatted = Cache::remember('popular-games', 60, function () use($before, $after) {
            return $this->popularGames = Http::withHeaders(config('services.igdb'))
                                        ->withBody(
                                        'fields name, rating, cover.url, platforms.abbreviation,slug;
                                        where(first_release_date > '.$before.'& first_release_date < '.$after.');
                                        where rating != null;
                                        sort rating desc;
                                        limit 12;'
                                        ,'text/plain')
                                        ->post('https://api.igdb.com/v4/games')
                                        ->json();
        });

        //dump($this->formatForView($popularGamesUnformatted));

        $this->popularGames = $this->formatForView($popularGamesUnformatted);
    }

    public function render()
    {
        return view('livewire.popular-games');
    }

    private Function formatForview($games)
    {
        return collect($games)->map( function($game){
            return collect($game)->merge([
                'coverImageURL'=>  isset($game['cover']) ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) : '#' ,
                'rating'=> isset($game['rating']) ? round($game['rating']).'%' : null,
                'platforms'=>collect($game['platforms'])->pluck('abbreviation')->implode(', '),
            ]);
        });
    }
}
