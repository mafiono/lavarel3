<?php

namespace App;

use App\Models\UserDocumentAttachment;
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
     * @return UserDocument|false
     *
     */
    public static function saveDocument($user, UploadedFile $file, $type)
    {
        $dir = storage_path().DIRECTORY_SEPARATOR.'documentacao'.DIRECTORY_SEPARATOR.$type;
        if (!file_exists($dir)) mkdir($dir);

        $ext = $file->getClientOriginalExtension() ?: 'none';

        $fileName = $user->id.'_'.str_random(10).'.'.$ext;
        $fileMoved = $file->move($dir, $fileName);
        if (! File::exists($fileMoved))
            return false;    

        $newDoc = new UserDocument();
        $data = [
            'user_id' => $user->id,
            'type' => $type,
            'file' => $fileName,
            'description' => $file->getClientOriginalName(),
            'user_session_id' => UserSession::getSessionId()
        ];

        foreach ($data as $key => $value)
            $newDoc->$key = $value;

        if (!$newDoc->save())
            return false;

        $newAtt = new UserDocumentAttachment();
        $newAtt->user_id = $user->id;
        $newAtt->user_document_id = $newDoc->id;
        $newAtt->data = $file;

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
}
