var BetsService = new (function() {
    var handlers = [];
    var onConnectHandler = null;
    var onDisconnectHandler = null;
    var onErrorHandler = null;
    var socket = null;
    var lastHost = "";

    this.connect = function(host) {
        lastHost = host;
        socket = new WebSocket(host);
        socket.onopen = onConnectHandler;
        // Temporary auto reconnect
        socket.onclose = function () {
            setTimeout(function() {
                BetsService.connect(host);
            },2000);
            if (onDisconnectHandler)
                onDisconnectHandler();
        };
        socket.onmessage = responseHandler;
    };

    this.addHandler = function(handler) {
        handlers.push(handler);
    };

    this.removeHandler = function(handler) {
        for (var i=0; i<handlers.length; i++)
            if (handlers[i]==handler)
                handlers.splice(i,1);
    };

    this.onConnect = function(handler) {
        onConnectHandler = handler;
    };

    this.onDisconnect = function(handler) {
        onDisconnectHandler = handler;
    };

    this.onError = function(handler) {
        onErrorHandler = handler;
    };

    this.request = function (request) {
        //console.log("Request: " + request.rid);
        //console.log(request);
        if (socket.readyState === 1) {
            socket.send(JSON.stringify(request));
        } else {
            //Todo: inject a request on a reconnect.
            document.location.reload();
        }
    };

    function responseHandler(response) {
        response = JSON.parse(response.data);
        //console.log("Response: " + response.rid);
        //console.log(response);
        for (var i=0; i<handlers.length; i++)
            (handlers[i])(response);
    };
})();
