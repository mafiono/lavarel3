@extends('portal.profile.layout', [
    'active1' => 'BANCO',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'SALDO'])

@section('sub-content')

    <div class="col-xs-7 lin-xs-10 fleft">
        <div class="box-form-user-info lin-xs-12">
            <div class="title-form-registo brand-title brand-color aleft">
                Erro
            </div>

            @include('portal.messages')

        </div>
    </div>
    <div class="clear"></div>

    @include('portal.profile.bottom')    

@stop

@section('scripts')

{!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
{!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

@stop

