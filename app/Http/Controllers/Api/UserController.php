<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
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

    /**
     * Post the change of user
     * @return \Illuminate\Http\JsonResponse
     */
    public function postProfile()
    {
        $inputs = $this->request->only('profession','country', 'address', 'city', 'zip_code', 'phone');

        $validator = Validator::make($inputs, User::$rulesForUpdateProfile, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        if (! $this->authUser->updateProfile($inputs))
            return Response::json(['status' => 'error', 'type' => 'error',
                'msg' => 'Ocorreu um erro ao atualizar os dados do seu perfil, por favor tente mais tarde.']);

        Session::flash('success', 'Perfil alterado com sucesso!');

        return Response::json(['status' => 'success', 'type' => 'reload']);
    }
}