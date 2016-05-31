<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LegalDoc;
use Response;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    protected $authUser;
    protected $request;
    protected $userSessionId;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getDocInfo(Request $request) {
        $docId = $request->get('doc');
        $legalDoc = LegalDoc::getDoc($docId);
        return Response::json(compact('legalDoc'));
    }

    public function getChildesDocs(Request $request) {
        $docId = $request->get('doc');
        $childes = LegalDoc::getChildes($docId);
        return Response::json(compact('childes'));
    }
}