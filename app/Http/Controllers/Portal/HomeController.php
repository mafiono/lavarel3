<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use App\Models\WellComeLog;
use Validator;
use View, Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $authUser;

    protected $request;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->authUser = Auth::user();
        View::share('authUser', $this->authUser, 'request', $request);        
    }
    /**
     * Display homepage
     *
     * @return \View
     */
    public function index()
    {
        $phpAuthUser = $this->authUser?[
            "id" => $this->authUser->id,
            "auth_token" => $this->authUser->api_password
        ]:null;

        $competitions = Highlight::competitions()->get(['highlight_id']);

        return view('portal.index',  compact("phpAuthUser", "competitions"));
    }

    /**
     * @return View
     */
    public function wellcome()
    {
        $msg = '';
        $error = null;
        $email = $this->request->get('email', '');
        if ($this->request->isMethod('POST')) {
            try {
                $validator = Validator::make($this->request->all(), [
                    'email' => 'required|email|unique:wellcome,email',
                ], [
                    'email.required' => 'Indique um email por favor',
                    'email.email' => 'Indique um email válido por favor',
                    'email.unique' => 'Este email já está subscrito'
                ]);
                if (!$validator->fails()) {
                    $entry = new WellComeLog();
                    $entry->email = $this->request->get('email');
                    if ($entry->save()) {
                        $msg = 'Gravado com sucesso';
                    }
                } else {
                    $error = $validator->errors()->get('email')[0];
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        return view('portal.wellcome-page', compact('msg', 'error', 'email'));
    }
}
