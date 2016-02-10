<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

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
    * @param array data
    *
    * @return boolean true or false
    */
    public function saveDocument($user, $file, $type, $userSessionId)
    {
        $dir = storage_path().DIRECTORY_SEPARATOR.'documentacao'.DIRECTORY_SEPARATOR.$type;
        if (!file_exists($dir)) mkdir($dir);

        $fileName = $user->id.'_'.str_random(10).'.pdf';
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
