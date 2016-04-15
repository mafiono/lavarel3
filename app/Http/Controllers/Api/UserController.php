<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;

class UserController extends Controller {
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->authUser = Auth::user();
    }

    /**
     * Get the user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        $user = $this->authUser->profile->toArray();
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }
}