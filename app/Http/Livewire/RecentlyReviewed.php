<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class RecentlyReviewed extends Component
{
    public $recentlyReviewed = [];

    public function loadRecentlyReviewed()
    {
        $recentlyReviewedUnformatted = Cache::remember('recently-reviewed', 60, function () {
            return $this->recentlyReviewed = Http::withHeaders( config( 'services.igdb' ) )
                ->withBody(
                    'fields name, rating, cover.url, platforms.abbreviation,summary, rating_count;
                                where rating != null
                                & rating_count > 5;
                                sort rating desc;
                                limit 3;'
                    , 'text/plain' )
                ->post( 'https://api.igdb.com/v4/games' )
                ->json();
        });

        $this->recentlyReviewed = $this->formatForView($recentlyReviewedUnformatted);
    }

    public function render()
    {
        return view('livewire.recently-reviewed');
    }

    private function formatForView($games)
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
