var BetsService = new (function() {
    var handlers = [];
    var onConnectHandler = null;
    var onDisconnectHandler = null;
    var onErrorHandler = null;
    var socket = null;

    this.connect = function(host) {
        socket = new WebSocket(host);
        socket.onopen = onConnectHandler;
        // Temporary auto reconnect
        socket.onclose = function () {
            onDisconnectHandler();
            setTimeout(function() {
                BetsService.connect(host);
            },2000);

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
        socket.send(JSON.stringify(request));
    };

    function responseHandler(response) {
        response = JSON.parse(response.data);
        //console.log("Response: " + response.rid);
        //console.log(response);
        for (var i=0; i<handlers.length; i++)
            (handlers[i])(response);
    };
})();
