<!---- CONTENT ---->
<div class="col-xs-12 home-back">
    <div class="main-contend main-opacity standalone">
        <div class="main white-back">
            @include('portal.partials.pop_header', ['text' => 'ID JOGADOR: '.$authUser->internalId()])

            <div class="form-registo">
                <div class="col-xs-12 lin-xs-12 fleft">
                                        
                    <div class="col-xs-32 lin-xs-11 fleft">
                        <div class="box-form-user border-form-registo lin-xs-12">
                            <div class="col-xs-10 fcenter title-form-registo brand-title brand-color aleft">
                                Opções de Utilizador
                            </div>
                            <?php $active == 'PERFIL' ? $class = 'brand-botao' : $class = '';?>
                            <div class="registo-form">
                                <a href="/perfil">
                                    <div class="col-xs-10 brand-botao-rev2 brand-trans {{$class}}">
                                        PERFIL
                                    </div>
                                </a>
                            </div>

                            <?php $active == 'BANCO' ? $class = 'brand-botao' : $class = '';?>
                            <div class="registo-form">
                                <a href="/banco/saldo">
                                    <div class="col-xs-10 brand-botao-rev2 brand-trans {{$class}}">
                                        BANCO
                                    </div>
                                </a>
                            </div>

                            <?php $active == 'PROMOÇÕES' ? $class = 'brand-botao' : $class = '';?>
                            <div class="registo-form">
                                <a href="/promocoes">
                                    <div class="col-xs-10 brand-botao-rev2 brand-trans {{$class}}">
                                        PROMOÇÕES
                                    </div>
                                </a>
                            </div>
                            
                            <?php $active == 'COMUNICAÇÃO' ? $class = 'brand-botao' : $class = '';?>
                            <div class="registo-form">
                                <a href="/comunicacao/definicoes">
                                    <div class="col-xs-10 brand-botao-rev2 brand-trans {{$class}}">
                                        COMUNICAÇÃO
                                    </div>
                                </a>
                            </div>
                            
                            <?php $active == 'CONVIDAR AMIGOS' ? $class = 'brand-botao' : $class = '';?>
                            <div class="registo-form">
                                <a href="/amigos">
                                    <div class="col-xs-10 brand-botao-rev2 brand-trans {{$class}}">
                                        CONVIDAR AMIGOS
                                    </div>
                                </a>
                            </div>
                            
                            <?php $active == 'HISTÓRICO' ? $class = 'brand-botao' : $class = '';?>
                            <div class="registo-form">
                                <a href="/historico">
                                    <div class="col-xs-10 brand-botao-rev2 brand-trans {{$class}}">
                                        HISTÓRICO
                                    </div>
                                </a>
                            </div>
                            
                            <?php $active == 'JOGO RESPONSÁVEL' ? $class = 'brand-botao' : $class = '';?>
                            <div class="registo-form">
                                <a href="/jogo-responsavel">
                                    <div class="col-xs-10 brand-botao-rev2 brand-trans {{$class}}">
                                        JOGO RESPONSÁVEL
                                    </div>
                                </a>
                            </div>
                            
                        </div>                                
                    </div>