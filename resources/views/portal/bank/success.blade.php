@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'saldo'])

@section('sub-content')

    <div class="col-xs-7 lin-xs-10 fleft">
        <div class="box-form-user-info lin-xs-12">
            <div class="title-form-registo brand-title brand-color aleft">
                Sucesso
            </div>
        </div>
    </div>
@stop

@section('scripts')

{!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
{!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

@stop

