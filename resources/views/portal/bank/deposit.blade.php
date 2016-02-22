@extends('layouts.portal')


@section('content')

    @include('portal.profile.head', ['active' => 'BANCO'])

    @include('portal.bank.head_bank', ['active' => 'DEPOSITAR'])

        <div class="col-xs-7 lin-xs-10 fleft">
            <div class="box-form-user-info lin-xs-12">
                <div class="title-form-registo brand-title brand-color aleft">
                    Depositar
                </div>
                @if ($selfExclusion)
                    <div class="brand-descricao descricao-mbottom aleft">
                        O utilizador está auto-excluido.
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi, itaque laudantium quidem quisquam quod tenetur! Eligendi impedit nisi pariatur quis voluptatem! Ab aliquid consectetur doloremque inventore nemo non officiis veritatis.
                    </div>
                @else
                {!! Form::open(array('route' => 'banco/depositar', 'class' => 'form', 'id' => 'saveForm')) !!}
                <div class="registo-form">
                    <label>Selecione metodo de Pagamento</label>
                    <select class="col-xs-5" name="payment_method">
                        <option value="paypal" selected="selected">PayPal</option>
                    </select>
                </div>

                <div class="title-form-registo brand-title mini-mtop brand-color aleft">
                    » Paypal
                </div>
                <div class="form-box-contend">
                    <div class="lin-xs-5">
                        <div class="col-xs-3 fleft">
                            <label class="col-xs-12" style="line-height:25px;">Valor do Depósito:</label>
                        </div>
                        <div class="col-xs-4 fleft">
                            <input class="col-xs-9" type="text" name="deposit_value" id="deposit_value" />
                            <span class="has-error error" style="display:none;"> </span>
                        </div>
                        <span></span>
                        <div class="col-xs-4 fleft">
                            <input type="submit" class="col-xs-10 brand-botao brand-link " value="Efetuar Depósito" />
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                {!! Form::close() !!}
                @endif
            </div>
        </div>
    <div class="clear"></div>

    @include('portal.profile.bottom')
    
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/bank/deposit.js')) !!}

@stop

