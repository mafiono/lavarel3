window.GameLauncher = new (function() {
    const width = 1200;
    const height = 800;

    function open(url, gameId) {
        window.open(url + gameId, 'newwindow',
            'width=' + width + ', height=' + height + ', top='
            + ((window.outerHeight - height) / 2) + ', left=' + ((window.outerWidth - width) / 2)
        );
    }

    this.demo = function(gameId) {
        open('/casino/game-demo/', gameId)
    };

    this.open = function (gameId) {
        open('/casino/game/', gameId);
    }
})();