<?php
namespace App\Http\Controllers\Portal;

use App\CasinoGameTypes;
use App\CasinoGames;
use App\Http\Controllers\Controller;
use Session, View, Response, Auth;
use Illuminate\Http\Request;

class CasinoController extends Controller {
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request) {
        //$this->middleware('auth', ['except' => ['index', 'sports', 'loadPost']]);
        $this->request = $request;
        $this->authUser = Auth::user();
        $this->userSessionId = Session::get('userSessionId');
        View::share('authUser', $this->authUser, 'request', $request);
    }

    /**
     * Main casino view
     * @return View
     */
    public function casino() {
        return view('portal.casino.casino');
    }

    /**
     * Get casino game types
     * @return \Illuminate\Http\JsonResponse
     */
    public function gameTypes() {
        return Response::json(["game_types" => CasinoGameTypes::orderBy("position")->get(["id", "name", "css_icon"])]);
    }

    public function allGames() {
        $data = ["game_types" => []];
        array_push($data["game_types"], [
            "games" => CasinoGames::where("featured", "1")->get(["id", "name", "image_url"]),
            "gameType_name" => "Em destaque",
            "id" => "featured"
        ]);
        $gameTypes = CasinoGameTypes::where("id","!=","all")
            ->where("id","!=","featured")->orderBy("position")
            ->get(["id", "name", "css_icon"])->toArray();
        foreach ($gameTypes as $gameType) {
            array_push($data["game_types"], [
                "games" => CasinoGames::where("game_type_id", $gameType["id"])->get(["id", "name", "image_url"]),
                "gameType_name" => $gameType["name"],
                "id" => $gameType["id"]
            ]);
        }
        return Response::json($data);
    }

    /**
     * Get the games from a game type
     * @param $typeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function games($typeId) {
        return Response::json([
            "games" => CasinoGames::where("game_type_id", $typeId)->get(["id", "name", "image_url"]),
            "gameType_name" => CasinoGameTypes::find($typeId)->toArray()["name"]
        ]);
    }

    /**
     * Get the games from a game type
     * @return \Illuminate\Http\JsonResponse
     */
    public function featuredGames() {
        return Response::json([
            "games" => CasinoGames::where("featured", "1")->get(["id", "name", "image_url"]),
            "gameType_name" => "Em destaque"
        ]);
    }
}