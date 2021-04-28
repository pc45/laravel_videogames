<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class MostAnticipated extends Component
{
    public $mostAnticipated = [];

    public function loadMostAnticipated()
    {
        $current = Carbon::now()->timestamp;

        $this->mostAnticipated = Cache::remember('most-anticipated', 60, function () use($current) {
            return $this->mostAnticipated = Http::withHeaders( config( 'services.igdb' ) )
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
        return view('livewire.most-anticipated');
    }
}
