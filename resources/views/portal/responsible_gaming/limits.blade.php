@extends('layouts.portal')


@section('content')

        @include('portal.profile.head', ['active' => 'JOGO RESPONSÁVEL'])

        @include('portal.responsible_gaming.head_responsible_gaming', ['active' => 'LIMITES DE DEPÓSITO'])

        {!! Form::open(array('route' => array('jogo-responsavel/limites'),'id' => 'saveForm')) !!}
        <div class="col-xs-5 lin-xs-11 fleft">
            <div class="responsavel_main box-form-user-info lin-xs-12">
                <div class="title-form-registo brand-title brand-color aleft">
                    Limites de Depósito
                </div>

                <div class="brand-descricao mini-mbottom aleft">
                    <b>Por Favor, defina aqui os seus limites de depósito</b>
                </div>
                <div class="brand-descricao descricao-mbottom aleft">
                    Texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto
                </div>

                @include('portal.messages')

                <div class="col-xs-9 micro-mtop registo-form">
                    <label>Limite Diário</label>
                    <input class="col-xs-10" type="text" name="limit_betting_daily" id="limit_betting_daily" value="{{$authUser->limits->limit_betting_daily or ''}}" /><b> €</b>
                    <span class="has-error error" style="display:none;"> </span>
                </div>

                <div class="col-xs-9 micro-mtop registo-form">
                    <label>Limite Semanal</label>
                    <input class="col-xs-10" type="text" name="limit_betting_weekly" id="limit_betting_weekly" value="{{$authUser->limits->limit_betting_weekly or ''}}" /><b> €</b>
                    <span class="has-error error" style="display:none;"> </span>
                </div>

                <div class="col-xs-9 micro-mtop registo-form">
                    <label>Limite Mensal</label>
                    <input class="col-xs-10" type="text" name="limit_betting_monthly" id="limit_betting_monthly" value="{{$authUser->limits->limit_betting_monthly or ''}}"/><b> €</b>
                    <span class="has-error error" style="display:none;"> </span>
                </div>

                <div class="col-xs-8 mini-mtop">
                    <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Guardar" />
                </div>
            </div>
        </div>
        <div class="clear"></div>
        {!! Form::close() !!}

        @include('portal.profile.bottom')
                        
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

    <script type="text/javascript">

        var rules = {
            limit_deposit_daily: {
                number: true
            },
            limit_deposit_weekly: {
                number: true
            },        
            limit_deposit_monthly: {
                number: true
            },  
        };

        var messages = {
            limit_deposit_daily: {
                number: "Apenas dígitos são aceites no formato x.xx",
            },                
            limit_deposit_weekly: {
                number: "Apenas dígitos são aceites no formato x.xx",
            },                
            limit_deposit_monthly: {
                number: "Apenas dígitos são aceites no formato x.xx",
            }            
        };
            
    </script>

@stop