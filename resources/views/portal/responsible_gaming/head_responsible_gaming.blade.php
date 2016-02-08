<div class="col-xs-32 lin-xs-11 fleft">
    <div class="box-form-user border-form-registo lin-xs-12">
        <div class="col-xs-10 fcenter title-form-registo brand-title brand-color aleft">
            Jogo Responsável
        </div>
        
        <?php $active == 'LIMITES DE DEPÓSITO' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/jogo-responsavel/limites">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    LIMITES DE DEPÓSITO
                </div>
            </a>
        </div>

        <?php $active == 'LIMITES DE APÓSTAS' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/jogo-responsavel/limites/apostas">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    LIMITES DE APÓSTAS
                </div>
            </a>
        </div>

        <?php $active == 'AUTO-EXCLUSÃO' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/jogo-responsavel/autoexclusao">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    AUTO-EXCLUSÃO
                </div>
            </a>
        </div>
    </div>
</div>