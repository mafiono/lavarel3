<div class="col-xs-32 lin-xs-11 fleft">
    <div class="box-form-user border-form-registo lin-xs-12">
        <div class="col-xs-10 fcenter title-form-registo brand-title brand-color aleft">
            Promoções e Bónus
        </div>
        
        <?php $active == 'Por Utilizar' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/promocoes">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    Por Utilizar
                </div>
            </a>
        </div>

        <?php $active == 'Pendentes' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/promocoes/pendentes">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    Pendentes
                </div>
            </a>
        </div>

        <?php $active == 'Utilizados' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/promocoes/utilizados">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    Utilizados
                </div>
            </a>
        </div>        
    </div>
</div>