<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\UserBet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function index()
    {
        return Promotion::whereActive(true)
            ->whereType('sports')
            ->get();
    }

    public function getImage(Request $request)
    {
        $path = config('app.promotions_images_path') . '/' . $request->image;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);

        return response(file_get_contents($path), 200)
            ->header('Content-Type', $mime);

    }

    public function bigodd(Request $request)
    {
        $position = 1;

        $date = $request->exists('previous')
            ? Carbon::now()->subMonth(1)
            : Carbon::now();

        return UserBet::whereType('multi')
            ->where('amount', '>=', 5)
            ->whereResult('won')
            ->whereYear('updated_at', '=', $date->year)
            ->whereMonth('updated_at', '=', $date->month)
            ->whereDoesntHave('events', function ($query) {
                return $query->where('odd', '<', 1.3);
            })->orderBy('odd', 'desc')
            ->orderBy('amount', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->with('user')
            ->get()
            ->map(function ($bet) use (&$position) {
                return array_merge(
                    array_only($bet->toArray(),
                    ['amount', 'odd']) + ['username' => $bet->user->username, 'pos' => $position++]
                );
            });
    }
}