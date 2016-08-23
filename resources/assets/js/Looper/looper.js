Looper = new (function()
{
    var handles = [];

    var loop;

    init();

    function init()
    {
        loop = window.setInterval;
    }

    this.add = function(callbackFunc, milliseconds)
    {
        handles.push(loop(callbackFunc, milliseconds));
    };

    this.count = function()
    {
        return handles.length;
    }
});

window.setInterval = function (callbackFunc, milliseconds)
{
    Looper.add(callbackFunc, milliseconds);
};
