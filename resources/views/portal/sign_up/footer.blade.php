<div class="form-footer-rodape row">
    <div class="col-xs-4 form-submit acenter fleft">
    </div>
    <div class="col-xs-4 form-marcadores acenter fleft">
        @for($i = 1; $i <= 4; $i++)
            @if($step == $i)
                <p class="brand-botao">{{$i}}</p>
                @else
                <p>{{$i}}</p>
            @endif
        @endfor
    </div>
    <div class="col-xs-4 form-submit fleft">
        <div class="row">
            <div class="col-xs-offset-2 col-xs-9 btns aright">
                <input type="submit" class="col-xs-5 brand-botao brand-link formSubmit fright" value="Concluir" />
                @if (!empty($skip))
                    <a href="{{$skip}}" class="col-xs-6 btn btn-brand fleft">Saltar Passo</a>
                @endif
                @if (!empty($back))
                    <a href="{{$back}}" class="col-xs-6 btn btn-brand fleft">Voltar</a>
                @endif
                @if (!empty($play))
                    <a href="{{$play}}" class="col-xs-6 btn btn-brand fleft">Jogar</a>
                @endif
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>