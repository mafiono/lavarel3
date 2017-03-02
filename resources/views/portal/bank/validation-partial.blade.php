<div class="col-xs-6">
    <div class="title">
        {{$name}}
    </div>
    <div style="margin-top:5px; margin-bottom:20px;">
        @if ($status === 'confirmed')
            <div class="valido">Válido <img class="icon" src="/assets/portal/img/approved.png"></div>
        @elseif ($status === 'waiting_confirmation')
            <div class="pendente">Pendente <img class="icon" src="/assets/portal/img/pending.png"></div>
        @else
            <div class="invalido">Inválido <img class="icon" src="/assets/portal/img/declined.png"></div>
        @endif
    </div>
</div>