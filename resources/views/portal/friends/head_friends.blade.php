<div class="col-xs-32 lin-xs-11 fleft">
    <div class="box-form-user border-form-registo lin-xs-12">
        <div class="col-xs-10 fcenter title-form-registo brand-title brand-color aleft">
            Convidar Amigos
        </div>
        
        <?php $active == 'ENVIAR CONVITES' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/amigos/convites">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    ENVIAR CONVITES
                </div>
            </a>
        </div>

        <?php $active == 'REDE DE AMIGOS' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/amigos/rede">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    REDE DE AMIGOS
                </div>
            </a>
        </div>
    </div>
</div>