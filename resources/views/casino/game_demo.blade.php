<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$game->name}}</title>
    <style>
        html, body, iframe { box-sizing: border-box; padding: 0; margin: 0; height: 100%; }

        iframe { width: 100%; border:none; }
    </style>

</head>

<body style="height: 100%">
@if ($game->provider === 'netent')
    <script type="text/javascript" src="{{ config('app.netent_static_server') }}/gameinclusion/library/gameinclusion.js"></script>
    <div id="neGameClient"></div>
    <script type="text/javascript">
        var success = function(netEntExtend) { };

        var error = function(e) {
            if (e.code === 13)
                alert("Por favor permita a utilização de flash, para jogar este jogo.");
            else
                console.log("Something went wrong \nReason: " + e.message + "\nCode: " + e.code + "\nError:" + e.error);
        };

        netent.launch ({
            gameId: "{{ $game->short_id }}",
            staticServerURL: "{{ config('app.netent_static_server') }}",
            gameServerURL: "{{ config('app.netent_game_server') }}",
            sessionId: "DEMO_{{bin2hex(random_bytes(10))}}",
            lobbyURL: "{{ config('app.casino_lobby') }}",
            language: "pt",
            brandingLocale: "pt",
            enforceRatio: false,
            width: '100%',
            height: '100%'
        }, success, error);

    </script>
@else
    @if ($game->mobile)
        <script>
            window.location = "{!! config('app.isoftbet_launcher')."{$game->prefix}{$gameId}?lang=pt&cur=EUR&mode=0&table=12&background=0&lobbyURL=".config('app.casino_lobby') !!}";
        </script>
    @else
        <iframe src="{!! config('app.isoftbet_launcher')."{$game->prefix}{$gameId}?lang=pt&cur=EUR&mode=0&table=12&background=0&lobbyURL=".config('app.casino_lobby') !!}"
                frameborder="0" scrolling="no">
        </iframe>
    @endif
@endif
</body>
</html>