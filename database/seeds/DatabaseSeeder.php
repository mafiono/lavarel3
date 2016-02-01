<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserRolesTableSeeder::class);
        $this->call(UserSettingsTableSeeder::class);
        $this->call(DocumentTypesTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(SelfExclusionTypesTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);

        Model::reguard();
    }
}

class UserRolesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('user_bets')->delete();
        DB::table('user_transactions')->delete();
        DB::table('user_self_exclusions')->delete();
        DB::table('user_documentation')->delete();
        DB::table('user_settings')->delete();
        DB::table('user_limits')->delete();
        DB::table('user_balances')->delete();
        DB::table('user_statuses')->delete();
        DB::table('user_bank_accounts')->delete();
        DB::table('user_profiles')->delete();
        DB::table('user_sessions')->delete();
        DB::table('users')->delete();
        DB::table('users')->delete();
        DB::table('user_roles')->delete();

        DB::insert('insert into user_roles(id, name) values (?,?)', array('admin','Administrator'));
        DB::insert('insert into user_roles(id, name) values (?,?)', array('player','Player'));
    }
}


class UserSettingsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('user_settings')->delete();
        DB::table('settings')->delete();

        DB::insert('insert into settings(id, name, form_type) values (?,?,?)', array('email','Email','checkbox'));
        DB::insert('insert into settings(id, name, form_type) values (?,?,?)', array('telefone','Telefone','checkbox'));
        DB::insert('insert into settings(id, name, form_type) values (?,?,?)', array('sms','SMS','checkbox'));
        DB::insert('insert into settings(id, name, form_type) values (?,?,?)', array('correio','Correio','checkbox'));
        DB::insert('insert into settings(id, name, form_type) values (?,?,?)', array('newsletter','Newsletter','checkbox'));
        DB::insert('insert into settings(id, name, form_type) values (?,?,?)', array('chat','Chat','checkbox'));
    }
}

class DocumentTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('list_self_exclusions')->delete();
        DB::table('document_types')->delete();

        DB::insert('insert into document_types(id, name) values (?,?)', array('cartao_cidadao','Cartão de Cidadão'));
        DB::insert('insert into document_types(id, name) values (?,?)', array('bilhete_identidade','Bilhete de Identidade'));
        DB::insert('insert into document_types(id, name) values (?,?)', array('passaporte','Passaporte'));
    }
}

class StatusesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('statuses')->delete();

        DB::insert('insert into statuses(id, name) values (?,?)', array('active', 'Ativa'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('suspended', 'Suspensa'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('inactive', 'Desativada'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('canceled', 'Cancelada'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('step_3','Registo - Step 3'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('waiting_identity','A aguardar comprovativo'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('suspended_3_months','Suspensa 3 meses'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('suspended_6_months','Suspensa 6 meses'));
        DB::insert('insert into statuses(id, name) values (?,?)', array('suspended_1_year','Suspensa 1 ano'));
    }
}

class SelfExclusionTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('self_exclusion_types')->delete();

        DB::insert('insert into self_exclusion_types(id, name) values (?,?)', array('reflection_period', 'Prazo de reflexão'));
        DB::insert('insert into self_exclusion_types(id, name) values (?,?)', array('minimum_period', 'Período Mínimo'));
        DB::insert('insert into self_exclusion_types(id, name) values (?,?)', array('undetermined_period', 'Período Indeterminado'));
    }
}

class TransactionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('transactions')->delete();

        DB::insert('insert into transactions(id, name) values (?,?)', array('bank_transfer', 'Transferência Bancária'));
        DB::insert('insert into transactions(id, name) values (?,?)', array('payment_service', 'Pagamento de Serviços'));
        DB::insert('insert into transactions(id, name) values (?,?)', array('paypal', 'Paypal'));
    }
}





