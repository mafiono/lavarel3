Looper = new (function()
{
    var handles = [];

    var loop;

    var clearInt;

    init();

    function init()
    {
        loop = window.setInterval;
        clearInt = window.clearInterval;

        window.setInterval = function (callbackFunc, milliseconds)
        {
            // console.log('Add interval', handles.length);
            return Looper.add(callbackFunc, milliseconds);
        };
        window.clearInterval = function (handle)
        {
            let i = handles.indexOf(handle);
            if (i >= 0) delete handles[i];

            clearInt(handle);
            // console.log('Clear interval', handles.length);
        };
    }

    this.add = function(callbackFunc, milliseconds)
    {
        let id = loop(callbackFunc, milliseconds);
        handles.push(id);
        return id;
    };

    this.count = function()
    {
        return handles.length;
    };

    this.clear = function()
    {
        for (var i in handles)
          clearInt(handles[i]);

        handles = [];
    }
});

