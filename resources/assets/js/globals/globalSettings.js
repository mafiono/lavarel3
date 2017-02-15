GlobalSettings = function()
{
    var _old = localStorage.getItem('settings') || "{}";
    var settings =JSON.parse(_old) || {};
    //var settings = Cookies.getJSON("settings") || {};

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
