GlobalSettings = function()
{
    var settings = Cookies.getJSON("settings") || {};

    init();


    function init()
    {
        $(window).unload(persist);

        apply();
    }

    this.get = function()
    {
        return settings;
    };

    this.apply = function()
    {
        apply();
    };

    function apply()
    {

    }
};
