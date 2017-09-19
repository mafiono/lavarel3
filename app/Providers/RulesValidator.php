<?php
/**
 * Created by PhpStorm.
 * User: José.Couto
 * Date: 22/02/2017
 * Time: 11:07
 */

namespace App\Providers;

use App\Exceptions\IdentityException;
use App\User;
use App\UserProfile;
use DB;
use Exception;

class RulesValidator
{

    public static function validaNIF($nif, $ignoreFirst=true) {
        //Limpamos eventuais espaços a mais
        $nif=trim($nif);
        //Verificamos se é numérico e tem comprimento 9
        if (!is_numeric($nif) || strlen($nif)!==9) {
            return false;
        } else {
            $nifSplit=str_split($nif);
            // O primeiro digíto tem de ser 1, 2, 5, 6, 8 ou 9
            // Ou não, se optarmos por ignorar esta "regra"
            if (
                in_array($nifSplit[0], array(1, 2, 5, 6, 8, 9))
                ||
                $ignoreFirst
            ) {
                //Calculamos o dígito de controlo
                $checkDigit=0;
                for($i=0; $i<8; $i++) {
                    $checkDigit+=$nifSplit[$i]*(10-$i-1);
                }
                $checkDigit=11-($checkDigit % 11);
                //Se der 10 então o dígito de controlo tem de ser 0
                if($checkDigit>=10) $checkDigit=0;
                //Comparamos com o último dígito
                if ($checkDigit==$nifSplit[8]) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public static function checkIBAN($iban)
    {
        $iban = strtolower(str_replace(' ','',$iban));
        $Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
        $Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);

        if (array_key_exists(substr($iban,0,2), $Countries) && strlen($iban) == $Countries[substr($iban,0,2)])
        {
            $MovedChar = substr($iban, 4).substr($iban,0,4);
            $MovedCharArray = str_split($MovedChar);
            $NewString = "";

            foreach($MovedCharArray AS $key => $value){
                if(!is_numeric($MovedCharArray[$key])){
                    $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                }
                $NewString .= $MovedCharArray[$key];
            }

            if(bcmod($NewString, '97') == 1)
            {
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public static function ValidateNumeroDocumento($cc){
        // detectar os varios tipos
        $cc=str_replace(' ', '', trim((string)$cc));
        // BI
        if (self::ValidateBI($cc)) {
            return true;
        }
        // CC
        if (self::ValidateCC($cc)) {
            return true;
        }
        // Passaport
        return self::ValidatePassport($cc);
    }

    public static function ValidateBI($bi) {
        //Verificamos se é numérico e tem comprimento 9
        if (!is_numeric($bi) || strlen($bi)!==8) {
            return false;
        } else {
            return true;
        }
    }

    public static function ValidatePassport($pass) {
        if (preg_match('/^[a-zA-Z]{1,3}[0-9]{6,9}$/', $pass)) {
            return true;
        } else {
            return false;
        }
    }

    public static function ValidateCC($numeroDocumento)
    {
        try {
            $sum = (int) 0;
            $secondDigit = false;
            if(strlen($numeroDocumento) !== 12)
                return false;
            for ($i = strlen($numeroDocumento) - 1; $i >= 0; --$i) {
                $valor = self::GetNumberFromChar($numeroDocumento[$i]);
                if ($secondDigit) {
                    $valor *= 2;
                    if ($valor > 9)
                        $valor -= 9;
                }
                $sum += $valor;
                $secondDigit = !$secondDigit;
            }
            return ($sum % 10) === 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function GetNumberFromChar($letter)
    {
        switch($letter)
        {
            case '0' : return 0;
            case '1' : return 1;
            case '2' : return 2;
            case '3' : return 3;
            case '4' : return 4;
            case '5' : return 5;
            case '6' : return 6;
            case '7' : return 7;
            case '8' : return 8;
            case '9' : return 9;
            case 'A' : return 10;
            case 'B' : return 11;
            case 'C' : return 12;
            case 'D' : return 13;
            case 'E' : return 14;
            case 'F' : return 15;
            case 'G' : return 16;
            case 'H' : return 17;
            case 'I' : return 18;
            case 'J' : return 19;
            case 'K' : return 20;
            case 'L' : return 21;
            case 'M' : return 22;
            case 'N' : return 23;
            case 'O' : return 24;
            case 'P' : return 25;
            case 'Q' : return 26;
            case 'R' : return 27;
            case 'S' : return 28;
            case 'T' : return 29;
            case 'U' : return 30;
            case 'V' : return 31;
            case 'W' : return 32;
            case 'X' : return 33;
            case 'Y' : return 34;
            case 'Z' : return 35;
        }
        throw new Exception("Valor inválido no número de documento.");
    }

    public static function isDocumentUnique($docNr) {
        $docNr=str_replace(' ', '', trim((string)$docNr));
        $doc2 = $docNr;
        if (self::ValidateCC($docNr)) {
            $doc2 = substr($docNr, 0, 8);
        }

        $query = DB::table(User::alias('u'))
            ->leftJoin(UserProfile::alias('up'), 'up.user_id', '=', 'u.id')
            ->where('u.identity_checked', '=', 1)
            ->where(function($q) use($docNr, $doc2){
                $q->where('up.document_number', '=', $docNr);
                $q->orWhere('up.document_number', '=', $doc2);
            })
        ;

        return !($query->count() > 0);
    }

    public static function CleanCC($cc)
    {
        $cc=str_replace(' ', '', trim((string)$cc));
        if (self::ValidateCC($cc)) {
            return substr($cc, 0, 8);
        }
        if (self::ValidateBI($cc)) {
            return $cc;
        }
        $e = new IdentityException('type.passaporte', 'Must be using Passport!', 30);
        $e->setType('passaporte');
        throw $e;
    }
}