<div class="col-xs-11-bet fcenter">
    <div class="col-xs-45 height1 fleft">
        <!-- &nbsp;&nbsp; {{ \Carbon\Carbon::now()->format('D d M') }} -->
    </div>
    <div class="col-xs-32 height1 acenter fleft">
        1
    </div>
    <div class="col-xs-32 height1 acenter fleft">
        X
    </div>
    <div class="col-xs-32 height1 acenter fleft">
        2
    </div>
    
    <div class="clear"></div>
</div>

@if (!empty($competition['game']))
    @foreach ($competition['game'] as $game)

        @if (!empty($game['team1_name']) && !empty($game['team2_name']))
            <div class="middleGame" id="{{'game-'.$game['id']}}">

                <div class="col-xs-11-bet tabela-line-bet fcenter gameDescription">
                    <div class="col-xs-45 fleft">
                        <div class="col-xs-11-line grey3-back tabela-line height2 acenter">
                            <div class="col-xs-3 label-grey-brand-color fleft">
                                @if (!empty($game['start_ts']))
                                    {{ \Carbon\Carbon::createFromTimeStamp($game['start_ts'])->format('H:i')}}&nbsp;&nbsp;&nbsp;<i class="fa fa-star"></i>
                                @endif
                            </div>
                            <div class="col-xs-9 namming-bet height2 aleft fleft gameName" data-team1="{{$game['team1_name']}}" data-team2="{{$game['team2_name']}}">
                                @if (!empty($game['team1_name']))
                                    {{ $game['team1_name'] }}
                                    @if (!empty($game['team2_name']))
                                        - {{ $game['team2_name'] }}
                                    @endif
                                @endif
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    @if (!empty($game['markets_count']) && $game['markets_count'] > 0)

                        <?php $marketCount = 1;?>

                        @foreach ($game['market'] as $market)
                            @if (!empty($market['name']) && $market['name'] == 'Match Result')
                                <div class="middleMarket {{str_slug($market['name'],"-")}}" style="<?php echo $marketCount > 1 ? 'display:none;' : ''?>" id="{{'market-'.$market['id']}}">
                                    @if (!empty($market['event']))
                                            @foreach ($market['event'] as $event)
                                                <div class="middleEvent" id="{{'event-'.$event['id']}}">
                                                    <div class="col-xs-32 aright fleft">
                                                        <div class="col-xs-11 grey3-back tabela-line height2 tabela-bet acenter tabela-bet eventBet" data-eventid="{{$event['id']}}" data-eventName="{{$event['name']}}" data-eventodd="{{$event['price']}}">
                                                            {{ $event['price'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                    @endif
                                </div>
                                <?php $marketCount++;?>
                            @endif

                        @endforeach                    
                    @endif
                    <div class="clear"></div>
                </div>
            </div>
        @else

        @endif
    @endforeach
@endif