<div class="col-xs-32 lin-xs-11 fleft">
    <div class="box-form-user border-form-registo lin-xs-12">
        <div class="col-xs-10 fcenter title-form-registo brand-title brand-color aleft">
            Banco
        </div>
        
        <?php $active == 'SALDO' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/banco/saldo">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    SALDO
                </div>
            </a>
        </div>

        <?php $active == 'DEPOSITAR' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/banco/depositar">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    DEPOSITAR
                </div>
            </a>
        </div>

        <?php $active == 'CONTA DE PAGAMENTOS' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/banco/conta-pagamentos">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    CONTA DE PAGAMENTOS
                </div>
            </a>
        </div>

        <?php $active == 'LEVANTAR' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/banco/levantar">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    LEVANTAR
                </div>
            </a>
        </div>

    </div>
</div>