@extends('layouts.portal')


@section('content')

        @include('portal.profile.head', ['active' => 'PERFIL'])

        @include('portal.profile.head_profile', ['active' => 'AUTENTICAÇÃO'])
        @include('portal.profile.head_authentication', ['active' => 'IDENTIDADE'])
                        
        <div id="main_autentica_morada" class="col-xs-7 lin-xs-3 fleft" style="max-width: 400px;">
            <div class="box-form-user-info lin-xs-12">
                <div class="title-form-registo brand-title brand-color aleft">
                    Autenticação de Identidade
                </div>
                <div class="brand-descricao descricao-mbottom aleft">
                    <b class="neut-color">Estado da Conta:</b>
                    @if ($statusId == 'confirmed')
                        <font class="success-color" style="font-size:90%; text-decoration:underline;">CONTA CONFIRMADA</font> <i class="fa fa-check-circle success-color"></i>
                    @elseif ($statusId == 'waiting_confirmation')
                        <font class="info-color" style="font-size:90%; text-decoration:underline;">AGUARDAR CONFIRMAÇÃO</font> <i class="fa fa-exclamation-triangle info-color"></i>
                    @else
                        <font class="warning-color" style="font-size:90%; text-decoration:underline;">AGUARDAR COMPROVATIVO</font> <i class="fa fa-times-circle warning-color"></i>
                    @endif
                </div>
                @if ($statusId == 'waiting_document')
                <div id="autentica_morada_form" style="display:block; border-top:1px dashed #777; padding-top: 10px; ">
                    <div class="title-form-registo brand-title brand-color aleft">
                        Enviar Comprovativo
                    </div>

                    <div class="brand-descricao descricao-mbottom aleft">
                    Para confirmar a sua conta deverá submeter um comprovativo de identidade.
                        Para o efeito serão considerados comprovativos oficiais
                        (<b class="neut-color">Cartão de Cidadão</b>,
                        <b class="neut-color">Bilhete de Identidade</b>,
                        <b class="neut-color">Passaporte</b> ou <b class="neut-color">Carta de Condução</b>)
                        válido.
                    </div>

                    <div class="brand-descricao descricao-mbottom aleft">
                    Apenas são aceites documentos com tamanho máximo de <b class="neut-color">5mb</b>.
                    </div>

                    {!! Form::open(array('route' => array('perfil/autenticacao'),'id' => 'saveForm')) !!}
                        <div class="form-rodape">
                            <div class="col-xs-12 form-submit" style="margin-left:0;">
                                <input type="file" id="upload" name="upload" class="required col-xs-6 brand-botao brand-link upload-input" />
                                <span class="has-error error" style="display:none;"> </span>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="col-xs-32 acenter fright">
                            <button type="submit" class="col-xs-12 brand-botao acenter brand-botao-type brand-link formSubmit" data-loading-text="Aguarde...">Enviar</button>
                        </div>
                        <div class="clear"></div>
                    {!! Form::close() !!}
                </div>
                <div class="clear"></div>
                @endif
            </div>
        </div>

        @include('portal.profile.bottom')
                        
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