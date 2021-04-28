<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $before = Carbon::now()->subMonths(6)->timestamp;
        $after = Carbon::now()->addMonths(6)->timestamp;
        $current = Carbon::now()->timestamp;

        $highestRatedGames = Http::withHeaders(config('services.igdb'))
        ->withBody(
            'fields name, rating, cover.url, platforms.abbreviation;
            where rating != null;
            sort rating desc;
            limit 12;'
         ,'text/plain')
        ->post('https://api.igdb.com/v4/games')
        ->json();

        $recentlyReviewed = Http::withHeaders(config('services.igdb'))
            ->withBody(
            'fields name, rating, cover.url, platforms.abbreviation,summary, rating_count;
            where rating != null
            & rating_count > 5;
            sort rating desc;
            limit 3;'
            ,'text/plain')
            ->post('https://api.igdb.com/v4/games')
            ->json();

        $mostAnticapated = Http::withHeaders(config('services.igdb'))
            ->withBody(
                'fields *, first_release_date, cover.*;
              where(first_release_date > '.$before.'
              & first_release_date < '.$after.');
              sort rating desc;
              limit 4;'
            ,'text/plain')
            ->post('https://api.igdb.com/v4/games')
            ->json();

        $comingSoon = Http::withHeaders(config('services.igdb'))
            ->withBody(
               'fields *, first_release_date, cover.*;
            where(first_release_date > '.$current.');
            sort first_release_date desc;
            limit 4;'
               ,'text/plain')
            ->post('https://api.igdb.com/v4/games')
            ->json();

        return view('index', [
            'popularGames' => $highestRatedGames,
            'recentlyReviewed' => $recentlyReviewed,
            'mostAnticipated' => $mostAnticapated,
            'comingSoon' => $comingSoon,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
