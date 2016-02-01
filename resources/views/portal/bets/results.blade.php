<div class="col-xs-11-bet fcenter">
    <div class="col-xs-45 height1 fleft">
        &nbsp;&nbsp; {{ \Carbon\Carbon::now()->format('D d M') }}
    </div>
    <div class="col-xs-45 height1 acenter fleft">
        1
    </div>
    
    <div class="clear"></div>
</div>

@if (!empty($competition['game']))
    @foreach ($competition['game'] as $game)

        <div class="middleGame" id="{{'game-'.$game['id']}}">
            @if (!empty($game['markets_count']) && $game['markets_count'] > 0)

                <?php $marketCount = 1;?>

                @foreach ($game['market'] as $market)
                    <div class="middleMarket {{str_slug($market['name'],"-")}}" style="<?php echo $marketCount > 1 ? 'display:none;' : ''?>" id="{{'market-'.$market['id']}}">
                        @if (!empty($market['event']))
                            @foreach ($market['event'] as $event)
                                <div class="col-xs-11-bet tabela-line-bet fcenter" id="{{'event-'.$event['id']}}">
                                    <div class="col-xs-45 fleft">
                                        <div class="col-xs-11-line grey3-back tabela-line height2 acenter">
                                            <div class="col-xs-3 label-grey-brand-color fleft">

                                            </div>
                                            <div class="col-xs-9 namming-bet height2 aleft fleft">
                                                {{ $event['name'] }}
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-45 aright fleft">
                                        <div class="col-xs-11 grey3-back tabela-line height2 tabela-bet acenter tabela-bet">
                                            {{ $event['price'] }}
                                        </div>
                                    </div>
                                    <div class="clear"></div>                                                                                
                                </div>
                            @endforeach
                        @endif  
                    </div>
                    <?php $marketCount++;?>
                @endforeach
            @endif
        </div>

        <div class="col-xs-11-bet tabela-line-bet fcenter">
        </div>
    @endforeach
@endif