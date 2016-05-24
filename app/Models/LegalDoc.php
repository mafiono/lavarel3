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