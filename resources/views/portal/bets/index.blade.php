@extends('layouts.portal')


@section('content')

    <div class="col-xs-12">
        <div class="main-contend acenter">
            <h1>{{ $sportName.' > '.$regionName.' > '.$competitionName }}</h1>
            @foreach ($games as $key => $game)
                <h2> {{ $game['team1_name'].' vs '.$game['team2_name'] }} <span><a href="#" class="showMarkets">+</a></h2>
                <div class="markets" style="display:none">
                    @foreach($game['market'] as $keyMarket => $market)
                        <h3>  {{ $market['name'] }}  <span><a href="#" class="showEvents">+</a> </h3>
                        <div class="events" style="display:none">
                            @foreach($market['event'] as $keyEvent => $event)
                                <p>  {{ $event['name']}} - <b>{{$event['price']}}</b> </p>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
                        
@stop

@section('scripts')

    <script type="text/javascript">
    
        $(function(){
            $('.showMarkets').on('click', function(){

                $(this).parent().parent().next().toggle();
                return false;
            });

            $('.showEvents').on('click', function(){

                $(this).parent().parent().next().toggle();
                return false;
            });            
        });

    </script>


@stop