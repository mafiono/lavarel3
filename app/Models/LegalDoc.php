<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LegalDoc
 *
 * @property string id
 * @property string parent_id
 * @property int approved_version
 * @property int last_version
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class LegalDoc extends Model {
    protected $table = 'legal_docs';

    public static function getDoc($name)
    {
        $doc = self::find($name);
        return $doc != null ? $doc->approved: null;
    }

    public static function getChildes($name)
    {
        return self::query()
            ->leftJoin('legal_docs_versions as av', function ($join) {
                $join->on('av.legal_doc_id', '=', 'legal_docs.id');
                $join->on('av.version', '=', 'legal_docs.approved_version');
            })
            ->where('legal_docs.parent_id', '=', $name)
            ->orderBy('av.name')
            ->select('av.name')
            ->selectRaw('SUBSTR(legal_docs.id, LENGTH(legal_docs.parent_id)+2) as game')
            ->get();
    }

    /**
     * Relation Versions
     */
    public function versions() {
        return $this->hasMany('\App\Models\LegalDocVersion', 'legal_doc_id', 'id');
    }
    /**
     * Relation Approved Version
     */
    public function approved() {
        return $this->hasOne('\App\Models\LegalDocVersion', 'legal_doc_id', 'id')
            ->where('version', '=', $this->approved_version);
    }
}