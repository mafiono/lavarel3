<div class="col-xs-32 lin-xs-11 fleft">
    <div class="box-form-user border-form-registo lin-xs-12">
        <div class="col-xs-10 fcenter title-form-registo brand-title brand-color aleft">
            Perfil
        </div>
        
        <?php $active == 'INFO. PESSOAL' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/perfil">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    INFO. PESSOAL
                </div>
            </a>
        </div>

        <?php $active == 'AUTENTICAÇÃO' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/perfil/autenticacao">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    AUTENTICAÇÃO
                </div>
            </a>
        </div>

        <?php $active == 'PASSWORD' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/perfil/password">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    PASSWORD
                </div>
            </a>
        </div>
        
        <?php $active == 'CÓDIGO PIN' ? $class = 'brand-botao' : $class = '';?>
        <div class="registo-form">
            <a href="/perfil/codigo-pin">
                <div class="col-xs-11 brand-botao-rev2 brand-trans {{$class}}">
                    CÓDIGO PIN
                </div>
            </a>
        </div>
    </div>
</div>