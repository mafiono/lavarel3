<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$game->name}}</title>
    <style>
        html, body, iframe { box-sizing: border-box; padding: 0; margin: 0; height: 100%; background: #000;}

        iframe { width: 100%; border:none; }
    </style>
    <script type="text/javascript" src="{{ config('app.netent_static_server') }}/gameinclusion/library/gameinclusion.js"></script>
    <script>
        window.onbeforeunload = function() {
            var width = 700;
            var height = 800;
            var token = '{{$token->tokenid}}';

            window.open('/casino/game-details/' + token, token,
                    'width=' + width + ', height=' + height + ', top='
                    + ((window.outerHeight - height) / 2) + ', left=' + ((window.outerWidth - width) / 2)
            );
        }
    </script>
</head>
<body style="height: 100%">
@if ($game->provider === 'netent')
    <div id="neGameClient"></div>
    <script type="text/javascript">
        var success = function(netEntExtend) { };

        var error = function(e) {
            alert ("Something went wrong \nReason: " + e.message + "\nCode: " + e.code + "\nError:" + e.error);
        };

        netent.launch ({
            gameId: "{{ $game->id }}",
            staticServerURL: "{{ config('app.netent_static_server') }}",
            gameServerURL: "{{ config('app.netent_game_server') }}",
            sessionId: "{{ $sessionId }}",
            mobileParams: { lobbyUrl: 'https://google.com'},
            enforceRatio: false,
            width: '100%',
            height: '100%'
        }, success, error);

    </script>
@else
    <iframe src="{!! config('app.isoftbet_launcher')."{$game->prefix}{$game->id}?lang=pt&cur=EUR&mode=1&background=1&uid={$user->id}&user={$user->username}&token={$token->tokenid}&lobbyURL=".config('app.casino_lobby') !!}"
            frameborder="0" scrolling="no">
    </iframe>
@endif
</body>
</html>