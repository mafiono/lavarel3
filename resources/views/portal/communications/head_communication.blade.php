<div class="col-xs-32 lin-xs-11 fleft">
    <div class="box-form-user border-form-registo lin-xs-12">
        <div class="col-xs-10 fcenter title-form-registo brand-title brand-color aleft">
            Comunicação
        </div>
        
        <?php $active == 'MENSAGENS' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/comunicacao/mensagens">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    MENSAGENS
                </div>
            </a>
        </div>

        <?php $active == 'DEFINIÇÕES' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/comunicacao/definicoes">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    DEFINIÇÕES
                </div>
            </a>
        </div>
    </div>
</div>