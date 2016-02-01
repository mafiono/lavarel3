function EventHandler() {
    var handlers = [];

    this.addHandler = function(handler) {
        handlers.push(handler);
    };

    this.removeHandler = function(handler) {
        for (var i in handlers) {
            if (handlers[i]==handler)
                handlers.splice(i,1);
        }
    };

    this.exec = function() {
        for (var i in handlers)
            handlers[i].apply(this, arguments);
    }
}
