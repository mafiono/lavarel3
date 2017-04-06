<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
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
}