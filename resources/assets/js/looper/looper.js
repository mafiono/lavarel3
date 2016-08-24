Looper = new (function()
{
    var handles = [];

    var loop;

    init();

    function init()
    {
        loop = window.setInterval;

        window.setInterval = function (callbackFunc, milliseconds)
        {
            Looper.add(callbackFunc, milliseconds);
        };
    }

    this.add = function(callbackFunc, milliseconds)
    {
        handles.push(loop(callbackFunc, milliseconds));
    };

    this.count = function()
    {
        return handles.length;
    };

    this.clear = function()
    {
        for (var i in handles)
          clearInterval(handles[i]);

        handles = [];
    }
});

