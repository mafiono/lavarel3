<div class="col-xs-12">
    <div class="col-xs-45 jogo-box-botao blue-hover inline height1" id="live">Live</div>
    <div class="col-xs-45 jogo-box-botao blue-hover inline height1" id="preMatch">Pré-Match</div>
    <div class="col-xs-01 acenter inline height1"><i class="fa fa-chevron-left"></i></div>
</div>
<div class="col-xs-12 dyas-select">
    <select id="events-period-select" class="col-xs-10 jogo-box-botao height2">
        <option value="all" selected>Todos</option>
        <option value="24">24h</option>
        <option value="48">48h</option>

        <option value="Quarta-Feira">Quarta-Feira</option>
        <option value="Quinta-Feira">Quinta-Feira</option>
        <option value="Sexta-Feira">Sexta-Feira</option>
        <option value="Sábado">Sábado</option>
        <option value="Domingo">Domingo</option>
        <option value="Segunda-Feira">Segunda-Feira</option>
        <option value="Terça-Feira">Terça-Feira</option>
    </select>
</div>
<div class="col-xs-12 apostas-modalidades">
    <h2>
        Populares
    </h2>
    <ul>
        @foreach ($data['data']['data'] as $key => $sports)
        @foreach ($sports as $key1 => $sport)
        <li class="level1">
            <div class="menu1-option">
                <span><i class="i1 fa fa-chevron-down hidden"></i> </span><font class="n1">{{ $sport['alias'] }}</font>
            </div>
            @if (!empty($sport['region']))
            <ul>
                @foreach ($sport['region'] as $key2 => $region)
                <li class="level2 hidden">
                    <div class="menu2-option">
                        <span><i class="i2 fa fa-chevron-down hidden"></i> </span><font class="n2">{{ $region['name'] }}</font>
                    </div>
                    @if (!empty($region['competition']))
                    <ul>
                        @foreach ($region['competition'] as $key3 => $competition)
                        @if (!empty($competition['id']))
                        @if (!str_contains($competition['name'], 'Results') && !str_contains($competition['name'], "Ballon"))
                        <li class="hidden level3" data-id="{{ $competition['id'] }}">
                            <span><i class="i3 fa fa-chevron-down hidden"></i> </span><font class="n3">{{ $competition['name'] }}</font>
                        </li>
                        @endif
                        @endif
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>

            @endif
        </li>
        @endforeach
        @endforeach
    </ul>


