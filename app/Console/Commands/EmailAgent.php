<?php

namespace App\Console\Commands;

use App\Lib\Mail\SendMail;
use App\Models\Message;
use App\Models\UserMail;
use App\User;
use App\UserDocument;
use App\UserProfile;
use Carbon\Carbon;
use DB;
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
//        $this->checkInactiveAccounts(30, "Volte a apostar", SendMail::$TYPE_15_INACTIVE_30);
//        $this->checkInactiveAccounts(90, "Sentimos a sua falta", SendMail::$TYPE_16_INACTIVE_90);
//        $this->checkInactiveAccounts(120, "Conta inativa há 120 dias", SendMail::$TYPE_17_INACTIVE_120);
        $this->checkNewMessages();
        $this->checkIdentityExpired();
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
                'url' => '/confirmar_email?email='.$user->email.'&token='.$user->email_token,
            ];

            $this->sendEmail($user, $type, $vars);
            $this->line("User: $user->id -> $user->username -> $user->email");
        }
    }

    private function checkInactiveAccounts($days, $msg, $type)
    {
        $query = User::from(User::alias('u'))
            ->leftJoin(UserProfile::alias('up'), 'u.id', '=', 'up.user_id')
            ->leftJoin(UserMail::alias('um'), function ($join) use ($type) {
                $join->on('u.id','=', 'um.user_id');
                $join->where('um.type', '=', $type);
            })
            ->where('u.last_login_at', '<', Carbon::now()->subDays($days)->tz('UTC'))
            ->where('up.email_checked', '=', 1)
            ->whereIn('u.rating_status', ['approved', 'pre-approved'])
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
                'title' => $msg,
                'url' => '/promocoes',
            ];

            $this->sendEmail($user, $type, $vars);
            $this->line("User: $user->id -> $user->username -> $user->email");
        }
    }

    private function checkNewMessages()
    {
        $type = SendMail::$TYPE_22_NEW_MESSAGE;
        $query = Message::from(Message::alias('m'))
            ->leftJoin(User::alias('u'), 'u.id', '=', 'm.user_id')
            ->leftJoin(UserProfile::alias('up'), 'u.id', '=', 'up.user_id')

            ->where('m.viewed', '=', 0)
            ->where('m.created_at', '>', Carbon::now()->subHour(20)->startOfDay()->tz('UTC'))
            ->where('m.created_at', '<', Carbon::now()->subMinute(30)->tz('UTC'))
            ->groupBy('u.id')->groupBy('u.username')->groupBy('up.email')
            ->select([
                'u.id', 'u.username', 'up.email',
                DB::raw('count(*) as messages'),
            ])
        ;
//        dd($query->toSql(), $query->get());

        foreach ($query->get() as $user) {
            $vars = [
                'title' => 'Nova Mensagem',
                'url' => '/perfil/comunicacao/mensagens',
            ];

            $this->sendEmail($user, $type, $vars);
            $this->line("User: $user->id -> $user->username -> $user->email");
        }
    }

    private function checkIdentityExpired()
    {
        $type = SendMail::$TYPE_19_IDENTITY_EXPIRED;
        $query = User::from(User::alias('u'))
            ->leftJoin(UserProfile::alias('up'), 'u.id', '=', 'up.user_id')
            ->leftJoin(UserDocument::alias('ud'), 'u.id', '=', 'ud.user_id')
            ->leftJoin(UserMail::alias('um'), function ($join) use ($type) {
                $join->on('u.id','=', 'um.user_id');
                $join->on('ud.id','=', 'um.custom_id'); // special value
                $join->where('um.type', '=', $type);
            })
            ->where('ud.expire', '<', Carbon::now()->addDays(3))
            ->where('ud.status_id', '=', 'approved')
            ->where(function ($q) {
                $q->whereNull('um.id');
                $q->orWhere('um.sent', '=', 0);
            })
            ->select([
                'u.id', 'u.username',
                'up.email',
                'um.id as mailId', 'um.sent', 'um.resend',
            ])
        ;
//        dd($query->toSql(), $query->get());

        foreach ($query->get() as $user) {
            $vars = [
                'title' => 'Renovação de documentos',
                'url' => '/perfil/autenticacao',
            ];

            $this->sendEmail($user, $type, $vars);
            $this->line("User: $user->id -> $user->username -> $user->email");
        }
    }
}
