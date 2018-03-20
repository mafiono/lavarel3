<?php

namespace App\Http\Controllers;

use App\CasinoTransaction;
use App\Lib\DebugQuery;
use App\Models\Promotion;
use App\UserBet;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function index()
    {
        return Promotion::whereActive(true)
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
                return [
                    'username' => $bet->user->username,
                    'amount' => $bet->amount,
                    'odd' => $bet->odd,
                ];
            });
    }

    public function endurance(Request $request)
    {
        $games = explode(',', $request['game']);

        $startDate = Carbon::parse($request['start-date']);
        $interval = $request['interval'];

        $endDate = min(Carbon::now(), Carbon::parse($request['start-date'])->addDays($interval));

        if ($interval > 1) {
            $days = $endDate->diffInDays($startDate) + 1;
        } else {
            $days = $endDate->diffInDays($startDate);
        }

        $query = CasinoTransaction::select([
            'user_id',
            'created_at',
            DB::raw('COUNT(DISTINCT DATE( created_at )) AS days'),
            DB::raw('SUM( amount ) as totalAmount')
        ])
            ->where('type', '=', 'bet')
            ->where('amount', '>', 0)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->whereIn('game_id', $games)
            ->groupBy('user_id')
            ->having('days', '=', $days)
            ->orderBy('totalAmount', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->with('user')
        ;
//        DebugQuery::make($query);

        return $query
            ->get()
            ->map(function ($bet) use ($days) {
                return [
                    'username' => '***' . substr($bet->user->username, 3),
                    'days' => $days,
                    'amount' => $bet->totalAmount,
                ];
            });
    }
}