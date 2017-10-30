<html>
    <head>
        <title>CASINO PORTUGAL - Netent mobile plugin</title>
        <script type="text/javascript" src="{{ config('app.netent_static_server') }}/gameinclusion/library/gameinclusion.js"></script>
    </head>
    <body>
    <script type="text/javascript">
        var netEntExtend = netent.plugin;
        // Game ready.
        netEntExtend.addEventListener("gameReady", function() {
            window.onbeforeunload = function() {
                if (window.performance && window.performance.navigation.type === 1) {
                    return;
                }

                var xhttp = new XMLHttpRequest();

                xhttp.open('GET', '/casino/game/close/{{ $tokenId }}', true);

                xhttp.send();
            }
        });

        netEntExtend.call("pluginReady", [], function(success) {}, function(error) {});
    </script>
    </body>
</html>