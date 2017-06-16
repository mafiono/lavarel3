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
    <script type="text/javascript" src="https://casinoportugal-static-test.casinomodule.com/gameinclusion/library/gameinclusion.js"></script>
</head>
<body style="height: 100%">
<div id="neGameClient"></div>
<script type="text/javascript">
    var success = function(netEntExtend) { };
    var error = function(error) { };
    netent.launch ({
        gameId: "{{ $game->id }}",
        staticServerURL: "{{ config('app.netent_static_server') }}",
        gameServerURL: "{{ config('app.netent_game_server') }}",
        sessionId: "{{ $sessionId }}",
//        enforceRatio: false,
//        width: '100%',
//        height: '100%'
    }, success, error);
</script>
{{--<iframe src="{!! $url !!}" frameborder="0" scrolling="no"></iframe>--}}
</body>
</html>