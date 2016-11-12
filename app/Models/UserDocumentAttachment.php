<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LegalDoc
 *
 * @property int id
 * @property int user_id
 * @property int user_document_id
 * @property blob data
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class UserDocumentAttachment extends Model {
    protected $connection = 'docs_db';
    protected $table = 'user_document_attachments';

}