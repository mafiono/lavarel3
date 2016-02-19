<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * Creates a new user document
     *
     * @param $user
     * @param UploadedFile $file
     * @param $type
     * @param $userSessionId
     * @return bool true or false
     *
     */
    public function saveDocument($user, UploadedFile $file, $type, $userSessionId)
    {
        $dir = storage_path().DIRECTORY_SEPARATOR.'documentacao'.DIRECTORY_SEPARATOR.$type;
        if (!file_exists($dir)) mkdir($dir);

        $ext = $file->getExtension() ?: 'none';

        $fileName = $user->id.'_'.str_random(10).'.'.$ext;
        $fileMoved = $file->move($dir, $fileName);
        if (! File::exists($fileMoved))
            return false;    

        $data = [
            'user_id' => $user->id,
            'type' => $type,
            'file' => $fileName,
            'description' => $file->getClientOriginalName(),
            'user_session_id' => $userSessionId
        ];

        foreach ($data as $key => $value)
        	$this->$key = $value;

        if (!$this->save())
        	return false;

        $fullPath = $dir.DIRECTORY_SEPARATOR.$fileName;

        return $fullPath;
    }
}
