var TerminalVerifier = new (function() {

    var options = {
        warning: "O seu terminal não é tem resolução gráfica suficiente para poder jogar sem limitações."
    };

    init();

    function init()
    {
        verify();
    }

    function verify()
    {
        if (window.screen.width < 1200)
            render();
    }

    function render()
    {
        var container = $("#terminalVerifier-container");

        container.removeClass("hidden");

        container.html(Template.apply("terminalVerifier", options));

        container.find(".content").css("width", window.screen.width);

        container.find("#accept").click(acceptClick);
    }

    function acceptClick()
    {
        var container = $("#terminalVerifier-container");

        container.html("");

        container.addClass("hidden");
    }

})();
