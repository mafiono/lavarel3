<?php

namespace App;

use App\Models\UserDocumentAttachment;
use App\Traits\MainDatabase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UserDocument
 *
 * @property int id
 * @property int user_id
 * @property int staff_id
 * @property string type
 * @property string file
 * @property string description
 * @property int user_session_id
 * @property int staff_session_id
 * @property string status_id
 * @property Carbon expire
 * @property string motive
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class UserDocument extends Model
{
    use MainDatabase;
    protected $table = 'user_documentation';

    /**
     * Relation with User
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    /**
     * Relation with Status
     *
     */
    public function status()
    {
        return $this->hasOne('App\Status', 'id', 'status_id');
    }

    /**
     * Creates a new user document
     *
     * @param $user
     * @param UploadedFile $file
     * @param $type
     * @param $userSessionId
     * @return UserDocument|false
     *
     */
    public static function saveDocument($user, UploadedFile $file, $type, $userSessionId)
    {
        $ext = $file->getClientOriginalExtension() ?: 'none';

        $mimeType = File::mimeType($file->getRealPath());
        $dataFile = file_get_contents($file->getRealPath());
        if (strlen($dataFile) <= 0)
            return false;
        $fileName = $user->id.'_'.str_random(10).'.'.$ext;
        // unlink($file->getRealPath());

        $newDoc = new UserDocument();
        $data = [
            'user_id' => $user->id,
            'type' => $type,
            'file' => $fileName,
            'description' => $file->getClientOriginalName(),
            'user_session_id' => $userSessionId
        ];

        foreach ($data as $key => $value)
            $newDoc->$key = $value;

        if (!$newDoc->save())
            return false;

        $newAtt = new UserDocumentAttachment();
        $newAtt->user_id = $user->id;
        $newAtt->user_document_id = $newDoc->id;
        $newAtt->mime_type = $mimeType;
        $newAtt->data = $dataFile;

        if (!$newAtt->save())
            return false;

        return $newDoc;
    }

    public function getFullPath()
    {
        $dir = storage_path().DIRECTORY_SEPARATOR.'documentacao'.DIRECTORY_SEPARATOR.$this->type;
        $fullPath = $dir.DIRECTORY_SEPARATOR.$this->file;

        return $fullPath;
    }

    public function canDelete() {
        return $this->status_id === 'pending';
    }
}
