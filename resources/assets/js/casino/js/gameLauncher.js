window.GameLauncher = new (function() {
    const width = 1200;
    const height = 800;

    function open(url, gameId) {
        ga('send', { 'hitType': 'pageview', 'page': url + gameId, 'title': 'Game ' + gameId });
        if (window.MobileHelper.isMobile()) {
            window.location = url + gameId;
        } else {
            window.open(url + gameId, 'newwindow',
                'width=' + width + ', height=' + height + ', top='
                + ((window.outerHeight - height) / 2) + ', left=' + ((window.outerWidth - width) / 2)
            );
        }
    }

    this.demo = function(gameId) {
        ga('send', {
            hitType: 'event',
            eventCategory: 'play-demo',
            eventAction: 'play-' + gameId,
            eventLabel: 'Open Demo ' + gameId
        });
        open('/casino/game-demo/', gameId)
    };

    this.open = function (gameId) {
        ga('send', {
            hitType: 'event',
            eventCategory: 'play-game',
            eventAction: 'play-' + gameId,
            eventLabel: 'Open Game ' + gameId
        });
        open('/casino/game/', gameId);
    }
})();