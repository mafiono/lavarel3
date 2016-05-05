<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ProfessionalSituation;
use Response;
use Illuminate\Http\Request;

class UtilsController extends Controller
{
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getMixSignLists() {
        $countryList = Country::query()
            ->where('cod_num', '>', 0)
            ->orderby('name')
            ->get(['name as label', 'cod_alf2 as value'])
            ->toArray();
        $natList = Country::query()
            ->where('cod_num', '>', 0)->whereNotNull('nationality')
            ->orderby('nationality')
            ->get(['nationality as label', 'cod_alf2 as value'])
            ->toArray();
        $sitProfList = ProfessionalSituation::query()
            ->get(['name as label', 'id as value'])
            ->toArray();

        return Response::json(compact('countryList', 'natList', 'sitProfList'));
    }
}