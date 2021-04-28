<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ComingSoon extends Component
{

    public $comingSoon = [];

    public function loadComingSoon()
    {
        $current = Carbon::now()->timestamp;

        $this->comingSoon = Cache::remember('coming-soon', 60, function () use($current) {
            return $this->comingsoon = Http::withHeaders( config( 'services.igdb' ) )
                                        ->withBody(
                                        'fields *, first_release_date, cover.*;
                                        where(first_release_date > ' . $current . ');
                                        sort first_release_date desc;
                                        limit 4;'
                                        , 'text/plain' )
                                        ->post( 'https://api.igdb.com/v4/games' )
                                        ->json();
        });
    }

    public function render()
    {
        return view('livewire.coming-soon');
    }
}
