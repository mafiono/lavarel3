<div class="col-xs-12 jogo-box-botao">
    <div class="col-xs-8 height1 aleft fleft">
        <div class="col-xs-01 acenter inline">
            <i class="fa fa-chevron-left"></i>
            <i class="fa fa-chevron-left"></i>
        </div>
        <div class="inline">{{ $sportName }}</div>
        <div class="inline"> / {{ $regionName }}</div>
        <div class="inline"> / <b>{{ $competitionName }}</b></div>
    </div>
    <div class="col-xs-4 height1 fleft">
        <div class="col-xs-11 aright">Hora: {{ \Carbon\Carbon::now()->format('d M H:i') }}</div>
    </div>
    <div class="clear"></div>
</div>

<div class="col-xs-12 tabela-jogos aleft">
    <div class="col-xs-12 tabela-header blue-back">
        <div class="col-xs-11 fcenter">
            <div class="col-xs-7 fleft">
                <h2 class="height1">
                    {{ str_limit($competitionName, 40) }}
                </h2>
            </div>
            <div class="col-xs-5 acenter fleft">
                @if (!empty($marketsForSelect))
                    <div class="col-xs-12">
                        <select class="col-xs-5 blue-brand-back noborder height1 marketSelect">
                            @foreach ($marketsForSelect as $market)
                                @if (str_contains($competitionName, 'Results') || str_contains($competitionName, "Ballon"))
                                    <option value="{{$market}}">{{$market}}</option>
                                @else
                                    @if ($market == 'Match Result')
                                        <option value="{{$market}}">{{$market}}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="clear"></div>
        </div>
    </div>
    
    <div class="col-xs-12 tabela-body grey2-back">
    
        @if (str_contains($competitionName, 'Results') || str_contains($competitionName, "Ballon"))
            @include('portal.bets.results')
        @else
            @include('portal.bets.game')
        @endif            
        
    </div>
</div>
