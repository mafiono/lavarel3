<?php

namespace App\Console\Commands;

use App\Lib\Mail\SendMail;
use App\Models\UserMail;
use App\User;
use App\UserProfile;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Request;

class EmailAgent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email_agent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Agent';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkMailsNotValidated();
    }

    private function sendEmail($user, $type, $vars)
    {
        try {
            $mail = new SendMail($type);
            if ($user->mailId !== null) {
                $mail->retryMail($user->mailId);
            } else {
                $mail->prepareMail($user, $vars);
            }
            $mail->Send(true, $user->mailId !== null);

            $this->line('Success Sending Mail! to '. $user->id .':'. $user->username);
        } catch (Exception $e) {
            $this->error('Error sending mail to '. $user->id .':'. $user->username . ':'. $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }

    private function checkMailsNotValidated()
    {
        $type = SendMail::$TYPE_3_CONFIRM_EMAIL;
        $query = User::from(User::alias('u'))
            ->leftJoin(UserProfile::alias('up'), 'u.id', '=', 'up.user_id')
            ->leftJoin(UserMail::alias('um'), function ($join) use ($type) {
                $join->on('u.id','=', 'um.user_id');
                $join->where('um.type', '=', $type);
            })
            ->where('u.created_at', '<', Carbon::now()->subDays(3)->tz('UTC'))
            ->where('up.email_checked', '=', 0)
            ->where(function ($q) {
                $q->whereNull('um.id');
                $q->orWhere('um.sent', '=', 0);
            })
            ->select([
                'u.id', 'u.username',
                'up.email', 'up.email_token',
                'um.id as mailId', 'um.sent', 'um.resend',
            ])
        ;
//        dd($query->toSql(), $query->get());

        foreach ($query->get() as $user) {
            $vars = [
                'title' => 'Confirmação email',
                'url' => Request::getUriForPath('/').'/confirmar_email?email='.$user->email.'&token='.$user->email_token,
            ];

            $this->sendEmail($user, $type, $vars);
            $this->line("User: $user->id -> $user->username -> $user->email");
        }
    }
}
