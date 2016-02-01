@extends('layouts.portal')


@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity">
            <div class="main white-back">
                <div class="brand-back brand-box-title">
                    <div class="col-xs-2 main-logo fleft">
                        <img alt="ibetup" src="/assets/portal/img/main_logo.png" />
                    </div>
                    <div class="col-xs-10 brand-title aright white-color fleft">
                        @if (empty($selfExclusion) && empty($identity))
                            Em menos de <b>1 minuto</b> estará a jogar!
                        @endif
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="form-registo">
                    <div class="col-xs-2 lin-xs-12 fleft">
                        <div class="lin-xs-12 brand-back box-form-registo">
                            Banner Registo
                        </div>
                    </div>
                    <div class="col-xs-10 lin-xs-9 fleft">
                        <div class="col-xs-12 fleft">
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
                                    {!! Form::open(array('route' => array('/registar/step2'),'id' => 'saveForm', 'class' => 'confirma-spinner col-xs-6')) !!}
                                        <div class="registo-form" >
                                            <label>Comprovativo</label>
                                            <input type="file" id="upload" name="upload" class="required col-xs-6 brand-botao brand-link upload-input" accept="application/pdf" />
                                            <div class="clear"></div>
                                        </div>

                                        <div class="col-xs-9 form-submit aright fright">
                                            <input type="submit" class="col-xs-8 fleft brand-botao brand-link formSubmit" value="Continuar" />
                                        </div>
                                        <div class="clear"></div>
                                    {!! Form::close() !!}
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
                        </div>
                        <div class="clear"></div>

                        <div class="form-rodape">
                            @if (empty($selfExclusion) && empty($identity))
                                <div class="col-xs-12 form-marcadores acenter fright">
                                    <p>1</p>
                                    <p class="brand-botao">2</p>
                                    <p>3</p>
                                    <p>4</p>
                                </div>
                            @endif
                            <div class="clear"></div>
                        </div>
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
                        window.location = "/registar/step3";
                    }, 2000);            
            });
        <?php endif;?>

    </script>

@stop