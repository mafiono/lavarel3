@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'autenticacao',
    'form' => array('route' => array('perfil/autenticacao/morada'),'id' => 'saveForm'),
    'btn' => 'Enviar'])

@section('sub-content')

    @include('portal.profile.head_authentication', ['active' => 'morada'])

<div id="main_autentica_morada" class="col-xs-8 fleft">
    <div class="title-form-registo brand-title brand-color aleft">
        Autenticação de Morada
    </div>
    <div class="brand-descricao descricao-mbottom aleft">
        <b class="neut-color">Estado da Conta:</b>
        @if ($authUser->status->address_status_id == 'active')
            <font class="success-color" style="font-size:90%; text-decoration:underline;">CONTA CONFIRMADA</font> <i class="fa fa-check-circle success-color"></i>
        @elseif ($authUser->status->address_status_id == 'waiting_confirmation')
            <font class="info-color" style="font-size:90%; text-decoration:underline;">AGUARDAR CONFIRMAÇÃO</font> <i class="fa fa-exclamation-triangle info-color"></i>
        @else
            <font class="warning-color" style="font-size:90%; text-decoration:underline;">AGUARDAR COMPROVATIVO</font> <i class="fa fa-times-circle warning-color"></i>
        @endif
    </div>
    <div id="autentica_morada_form" style="display:block; border-top:1px dashed #777; padding-top: 10px; ">
        <div class="title-form-registo brand-title brand-color aleft">
            Enviar Comprovativo
        </div>

        <div class="brand-descricao descricao-mbottom aleft">
            Para confirmar a sua conta deverá submeter um comprovativo de titularidade da morada apresentada no seu registo. Para o efeito serão considerados comprovativos oficiais (Fatura <b class="neut-color">Água</b>, <b class="neut-color">Luz</b>, <b class="neut-color">Gás</b> ou <b class="neut-color">Telefone</b>) com data de emissão inferior a 3 meses.
        </div>

        <div class="brand-descricao descricao-mbottom aleft">
            Apenas são aceites documentos com tamanho máximo de <b class="neut-color">5mb</b>.
        </div>

        <div class="form-rodape" >
            <div class="col-xs-12 form-submit" style="margin-left:0;">
                <input type="file" id="upload" name="upload" required="required" class="required col-xs-6 brand-botao brand-link upload-input" />
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')) !!}

    <script>
        $("#authenticate-account-btn").on("click", function() {
            $("#autentica_morada_form").toggleClass("hidden");
            $("#documents-container").toggleClass("hidden");
        });

        var control = $("#upload"),
                clearBn = $("#clear");

        // Setup the clear functionality
        clearBn.on("click", function(){
            control.replaceWith( control.val('').clone( true ) );
        });

        // Some bound handlers to preserve when cloning
        control.on({
            change: function(){  },
            focus: function(){  }
        });
    </script>

@stop

