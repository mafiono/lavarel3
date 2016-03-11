@extends('layouts.portal', ['mini' => true])

@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity standalone">
            <div class="main white-back">
                @include('portal.partials.pop_header_signup', [
                'text' => empty($selfExclusion) && empty($identity) ?
                 'Em menos de <b>1 minuto</b> estará a jogar!': '',
                 'close' => false])
                
                <div class="form-registo">
                    <div class="col-xs-2 lin-xs-12 fleft">
                        <div class="lin-xs-12 banner-back box-form-registo">
                            <img src="/assets/portal/img/banners/banner_ronaldo.png" alt="Banner Registo">
                        </div>
                    </div>
                    <div class="col-xs-10 lin-xs-9 fleft">
                        <div class="col-xs-12 fleft">
                            {!! Form::open(array('route' => array('/registar/step2'),'id' => 'saveForm')) !!}
                            <div class="box-form-registo">
                                @if (empty($selfExclusion) && empty($identity))
                                    <div class="title-form-confirma brand-title brand-color acenter">
                                        estamos a criar a sua conta...
                                    </div>
                                     
                                    <div class="confirma-spinner acenter">                                    
                                        <i class="fa fa-spinner fa-pulse"></i>
                                        <p>em processamento</p>
                                    </div>

                                    <div class="col-xs-7 brand-descricao acenter fcenter">                                    
                                        Aguarde por favor enquanto procedemos à configuração da sua conta de jogador. Em alguns segundos estará pronto para jogar e ganhar...
                                    </div>
                                @elseif (empty($selfExclusion) && !empty($identity))
                                    <div class="title-form-confirma brand-title brand-color acenter">
                                        <p>Não conseguimos verificar a sua identidade a partir dos dados que introduziu, por favor forneça um documento de comprovativo.</p>
                                    </div>
                                    @include('portal.messages')
                                        <div class="registo-form col-xs-6 ">
                                            <label>Comprovativo</label>
                                            <input type="file" id="upload" name="upload" required="required" class="required col-xs-6 brand-botao brand-link upload-input" />
                                            <span class="has-error error" style="display:none;"> </span>
                                            <div class="clear"></div>
                                        </div>

                                        <div class="clear"></div>
                                    <div class="col-xs-7 brand-descricao acenter fcenter">
                                        <p>Clique <a href="/registar/step1">aqui</a> para voltar ao passo 1.</p>
                                    </div>
                                @else
                                    <div class="title-form-confirma brand-title brand-color acenter">
                                        <p>Lamentamos mas não podemos prosseguir com o registo.</p>
                                    </div>
                                    <div class="confirma-spinner acenter">
                                    </div>
                                    <div class="col-xs-7 brand-descricao acenter fcenter">
                                        @if (!empty($selfExclusion))
                                            <p>Motivo: Autoexclusão. Data de fim: <?php echo $selfExclusion->end_date?></p>
                                        @elseif (!empty($identity))
                                            <p>Não conseguimos verificar a sua identidade a partir dos dados que introduziu.</p>
                                        @endif
                                        <p>Clique <a href="/registar/step1">aqui</a> para voltar ao passo 1.</p>
                                    </div>
                                @endif
                                    
                            </div>
                            @if (empty($selfExclusion))
                                @include('portal.sign_up.footer', ['step' => 2, 'back' => '/registar/step1'])
                            @endif
                            {!! Form::close() !!}
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    
    <script type="text/javascript">

        <?php if (empty($selfExclusion) && empty($identity)):?>
            $(function(){
                setTimeout(
                    function() 
                    {
                        window.location = "/registar/step4";
                    }, 2000);            
            });
        <?php endif;?>

    </script>

@stop