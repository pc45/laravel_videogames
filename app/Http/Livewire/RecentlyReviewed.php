<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RecentlyReviewed extends Component
{
    public $recentlyReviewed = [];

    public function loadRecentlyReviewed()
    {
        $this->recentlyReviewed = Cache::remember('recently-reviewed', 60, function () {
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
    }

    public function render()
    {
        return view('livewire.recently-reviewed');
    }
}
