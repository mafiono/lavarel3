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

@if ($game->mobile)
    <script>
        window.location = "{!! config('app.isoftbet_launcher')."{$game->prefix}{$game->id}?lang=pt&cur=EUR&mode=0&table=12&background=0&lobbyURL=".config('app.casino_lobby') !!}";
    </script>
@else
    <iframe src="{!! config('app.isoftbet_launcher')."{$game->prefix}{$game->id}?lang=pt&cur=EUR&mode=0&table=12&background=0&lobbyURL=".config('app.casino_lobby') !!}"
            frameborder="0" scrolling="no">
    </iframe>
@endif

</body>
</html>