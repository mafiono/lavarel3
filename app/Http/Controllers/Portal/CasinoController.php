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
        $this->userSessionId = Session::get('user_session');
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
        return Response::json(["game_types" => CasinoGameTypes::types()]);
    }

    /**
     * Get all casino games
     * @return \Illuminate\Http\JsonResponse
     */
    public function allGames() {
        $data = ["game_types" => []];
        array_push($data["game_types"], [
            "games" => CasinoGames::featuredGames(),
            "gameType_name" => CasinoGameTypes::typeName("featured"),
            "id" => "featured"
        ]);
        $gameTypes = CasinoGameTypes::types(["all", "featured"]);
        foreach ($gameTypes as $gameType) {
            array_push($data["game_types"], [
                "games" => CasinoGames::games($gameType["id"]),
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
            "games" => CasinoGames::games($typeId),
            "gameType_name" => CasinoGameTypes::typeName($typeId)
        ]);
    }

    /**
     * Get the games from a game type
     * @return \Illuminate\Http\JsonResponse
     */
    public function featuredGames() {
        return Response::json([
            "games" => CasinoGames::featuredGames(),
            "gameType_name" => CasinoGameTypes::typeName("featured")
        ]);
    }
}