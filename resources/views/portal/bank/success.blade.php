@extends('layouts.portal', ['mini' => true])

@section('content')

    @include('portal.profile.head', ['active' => 'BANCO'])

    <div class="col-xs-7 lin-xs-10 fleft">
        <div class="box-form-user-info lin-xs-12">
            <div class="title-form-registo brand-title brand-color aleft">
                Sucesso
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

