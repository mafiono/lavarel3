<?phpnamespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\UserSetting;
use Input;
use Session, View, Response, Auth, Mail, Validator;
use Illuminate\Http\Request;
use App\JogadorConta, App\JogadorDefinicoes;

class CommunicationsController extends Controller
{
    protected $authUser;

    protected $request;

    protected $userSessionId;

    /**     * Constructor     *     * @return void     */    public function __construct(Request $request)    {        $this->middleware('auth');        $this->request = $request;        $this->authUser = Auth::user();        $this->userSessionId = Session::get('user_session');        View::share('authUser', $this->authUser, 'request', $request);    }    /**     * Display user comunicacao/definicoes page     *     * @return \View
     */
    public function settingsGet()
    {
        $settings = $this->authUser->settings()->first();

        return view('portal.communications.settings', compact('settings'));
    }
    /**     * Handle comunicacoes definicoes POST     *     * @return array Json array
     */

    public function settingsPost()
    {
        if (!UserSetting::updateSettings(Input::get(), Auth::user()->id, Session::get('user_session')))
            return Response::json( [ 'status' => 'error', 'msg' => 'Ocorreu um erro ao alterar as definições.' ] );

        return Response::json(['status' => 'success', 'msg' => 'Definições alteradas com sucesso.']);
    }
    /**
     * Display mensagens page
     *
     * @return \View
     */
    public function messagesGet()    {        return view('portal.communications.messages');    }
}
