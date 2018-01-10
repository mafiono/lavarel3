<?php

namespace App;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Session;

/**
 * @property int user_id
 * @property string user_session_id
 * @property int staff_id
 * @property string staff_session_id
 * @property string status_id
 * @property string identity_status_id
 * @property string email_status_id
 * @property string iban_status_id
 * @property string address_status_id
 * @property string selfexclusion_status_id
 * @property string motive
 * @property float balance
 * @property boolean current
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class UserStatus extends Model
{
    protected $table = 'user_statuses';

    protected $fillable = [
        'status_id',
        'selfexclusion_status_id'
    ];

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
        return $this->belongsTo('App\Status', 'status_id', 'id');
    }
    /**
     * Creates a new user status
     *
     * @param $user_id
     * @param $status
     * @param string $type
     *
     * @return object UserStatus
     */
    public static function setStatus($user_id, $status, $type = 'status_id')
    {
        $userId = $user_id ?? Auth::id() ?? Session::get('user_id');
        // Get current user Status
        /* @var $userStatus UserStatus */
        $userStatus = self::query()->where('user_id', '=', $userId)->where('current', '=', 1)->first();
        if ($userStatus == null){
            $userStatus = new UserStatus;
            $userStatus->user_id = $userId;
            $userStatus->status_id = 'pending';
            $userStatus->current = 1;
        } else {
            // force to save a new value in DB
            $userStatus->id = null;
            $userStatus->exists = false;
            $userStatus->staff_id = null;
            $userStatus->staff_session_id = null;
            $userStatus->motive = null;
            $userStatus->balance = null;
            $userStatus->created_at = null;
        }
        /* Set all user status to false */
        self::where('user_id', '=', $userId)
             ->update(['current' => '0']);

        switch ($type) {
            case 'identity_status_id': $userStatus->identity_status_id = $status; break;
            case 'email_status_id': $userStatus->email_status_id = $status; break;
            case 'address_status_id': $userStatus->address_status_id = $status; break;
            case 'iban_status_id': $userStatus->iban_status_id = $status; break;
            case 'selfexclusion_status_id':
                $userStatus->selfexclusion_status_id = $status;
                $userStatus->balance = UserBalance::getBalance($userId);
                switch ($status){
                    case null:
                        // Remove Self exclusion
                        break;
                    case 'undetermined_period':
                        $userStatus->motive = 'Utilizador pediu Auto-exlusão por tempo indeterminado.';
                        $userStatus->status_id = 'canceled';
                        break;
                    case 'reflection_period':
                        $userStatus->motive = 'Utilizador pediu periodo de reflexão.';
                        $userStatus->status_id = 'suspended';
                        break;
                    case 'minimum_period':
                    default:
                        $userStatus->motive = 'Utilizador pediu Auto-exlusão.';
                        $userStatus->status_id = 'suspended';
                        break;
                }

                break;
            case 'status_id':
            default: $userStatus->status_id = $status; break;
        }
        $userStatus->user_session_id = UserSession::getSessionId($userId);
        $tmpStatus = 'pending';
        if ($userStatus->selfexclusion_status_id !== null) {
            $tmpStatus = $userStatus->selfexclusion_status_id === 'undetermined_period' ? 'canceled':'suspended';
        } else if ($userStatus->identity_status_id === 'confirmed'){
            $tmpStatus = 'pre-approved';
            if ($userStatus->email_status_id === 'confirmed'
                && $userStatus->address_status_id === 'confirmed'
                && $userStatus->iban_status_id === 'confirmed'){
                $tmpStatus = 'approved';
            }
        } // else it stays pending
        // when can the front office change the Status?
        if ($userStatus->status_id !== $tmpStatus) {
            if (in_array($userStatus->status_id, ['pending', 'approved', 'pre-approved', 'suspended'], true)) {
                $userStatus->status_id = $tmpStatus;
            }
        }

        if (!$userStatus->save())
            return false;

        return $userStatus;
    }


    public function isApproved()
    {
        return $this->user->status->status_id === 'pre-approved' || $this->user->status->status_id === 'approved';
    }

    public function isSelfExcluded()
    {
        return $this->user->status->status_id == 'suspended';
    }
}
