Helpers = new (function ()
{
    this.updateOptions = function (from, to)
    {
        for (var i in from)
            to[i] = from[i];
    };

})();

