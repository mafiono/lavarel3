<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\LegalDoc;
use Carbon\Carbon;
use Response;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getBanners() {
        // alter date to future to see some specific Banner
        $now = Carbon::now();
        return response()->json(Banner::with('frames')
            ->whereActive(1)
            ->where('start', '<', $now)
            ->where(function ($q) use($now) {
                $q->whereNull('end');
                $q->orWhere('end', '>', $now);
            })
            ->get());
    }
}