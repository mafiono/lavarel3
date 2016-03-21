@extends('portal.profile.layout', [
    'active1' => 'BANCO',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'DEPOSITAR'])

@section('sub-content')

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
                    @include('portal.bank.deposit_partial')
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

