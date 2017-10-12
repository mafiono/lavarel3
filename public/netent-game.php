<html>
    <head>
        <script type="text/javascript" src="https://casinoportugal-static-test.casinomodule.com/gameinclusion/library/gameinclusion.js"></script>
    </head>
    <body>
    </body>
    <script type="text/javascript">
        var success = function(netEntExtend) { };
        var error = function(error) { };
        netent.launch ({
            gameId: "<?= $_GET['gameId'] ?>",
            staticServerURL: "<?= $_GET['staticServer'] ?>",
            gameServerURL: "<?= $_GET['gameServer'] ?>",
            sessionId: "<?= $_GET['sessionId'] ?>"
        }, success, error);
    </script>
</html>