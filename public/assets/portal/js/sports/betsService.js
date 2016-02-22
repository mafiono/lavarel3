var BetsService = new (function() {
    var handlers = [];
    var onConnectHandler = null;
    var onDisconnectHandler = null;
    var onErrorHandler = null;
    var socket = null;
    var host = "";
    var reconnectTimeout = null;

    this.connect = function(serviceHost) {
        reconnectTimeout = null;
        if (serviceHost)
            host = serviceHost;
        socket = new WebSocket(host);
        socket.onopen = onConnectHandler;
        socket.onclose = onDisconnectHandler;
        socket.onerror = onErrorHandler;
        socket.onmessage = responseHandler;
    };

    this.reconnect = function() {
        if (!reconnectTimeout)
            reconnectTimeout = setTimeout(BetsService.connect, 2000);
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

    this.onDisconnectHandler = function(handler) {
        onDisconnectHandler = handler;
    };

    this.onError = function(handler) {
        isConnecting = false;
        onErrorHandler = handler;
    };

    this.request = function (request) {
        //console.log("Request: " + request.rid);
        //console.log(request);
        if (socket.readyState === 1) {
            socket.send(JSON.stringify(request));
        } else {
            this.reconnect();
            //document.location.reload();
        }
    };

    function responseHandler(response) {
        response = JSON.parse(response.data);
        //console.log("Response: " + response.rid);
        //console.log(response);
        for (var i=0; i<handlers.length; i++)
            (handlers[i])(response);
    }

})();
