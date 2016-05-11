<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ListIdentityCheck;
use App\ListSelfExclusion;
use App\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use JWTAuth;
use Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class SignUpController extends Controller {
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function postStep1(Request $request) {
        $inputs = $request->all();

        $sitProf = $inputs['sitprofession'];
        if (in_array($sitProf, ['44','55','77','88'])){
            $sitProfList = [
                '' => '',
                '11' => 'Trabalhador por conta própria',
                '22' => 'Trabalhador por conta de outrem',
                '33' => 'Profissional liberal',
                '44' => 'Estudante',
                '55' => 'Reformado',
                '66' => 'Estagiário',
                '77' => 'Sem atividade profissional',
                '88' => 'Desempregado',
                '99' => 'Outra',
            ];
            $inputs['profession'] = $sitProfList[$sitProf];
        }

        $validator = Validator::make($inputs, User::$rulesForRegisterStep1, User::$messagesForRegister);
        if ($validator->fails()) {
            $messages = User::buildValidationMessageArray($validator);
            return Response::json( [ 'status' => 'error', 'msg' => $messages ] );
        }

        /*
        * Validar auto-exclusão
        */
        $selfExclusion = ListSelfExclusion::validateSelfExclusion($inputs);
        if ($selfExclusion) {
            return Response::json(['status' => 'error', 'msg' => 'Motivo: Autoexclusão. Data de fim:' . $selfExclusion->end_date]);
        }

        /*
        * Validar identidade
        */
        $identityStatus = ListIdentityCheck::validateIdentity($inputs) ? 'confirmed': 'waiting_document';
        $user = new User;
        try{
            if (!$userSession = $user->signUp($inputs, function(User $user) use($identityStatus) {
                /* Create User Status */
                return $user->setStatus($identityStatus, 'identity_status_id');
            })) {
                return Response::json(['status' => 'error', 'msg' => 'Motivo: Ocorreu um erro ao gravar os dados!']);
            }
        } catch (Exception $e) {
            return Response::json(['status' => 'error', 'msg' => 'Motivo: ' . trans($e->getMessage())]);
        }

        /*
         * Create token so the user gets logged.
         */
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['status' => 'error', 'msg' => 'Falha JWT no utilizador.'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['status' => 'error', 'msg' => 'Falha ao gerar token para o utilizador.'], 500);
        }

        return Response::json( [ 'status' => 'success', 'token' => $token, 'msg' => 'Utilizador registado com sucesso prossiga para o próximo passo.' ] );
    }
}