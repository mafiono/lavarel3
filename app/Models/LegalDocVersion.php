<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LegalDoc
 *
 * @property int id
 * @property string legal_doc_id
 * @property int version
 * @property string name
 * @property string description
 * @property boolean approved
 * @property int staff_id
 * @property int staff_session_id
 * @property int approved_staff_id
 * @property int approved_staff_session_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property LegalDoc doc
 */
class LegalDocVersion extends Model {
    protected $table = 'legal_docs_versions';

    public function doc() {
        return $this->hasOne('\App\Models\LegalDoc', 'id', 'legal_doc_id');
    }

}