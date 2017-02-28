TerminalVerifier = new (function() {
    var session = sessionStorage;
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
        var accept = session.getItem('small-screen') || null;
        if (window.innerWidth < 1200 && accept !== 'accept')
            render();
    }

    function render()
    {
        var container = $("#terminalVerifier-container");

        container.removeClass("hidden");

        container.html(Template.apply("terminalVerifier", options));

        container.find(".content").css("width", window.innerWidth);

        container.find("#accept").click(acceptClick);
    }

    function acceptClick()
    {
        var container = $("#terminalVerifier-container");

        container.html("");

        container.addClass("hidden");

        session.setItem('small-screen', 'accept');
    }

})();
