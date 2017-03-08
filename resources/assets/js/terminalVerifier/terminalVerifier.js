TerminalVerifier = new (function() {
    var session = sessionStorage;
    var options = {
        type: 'warning',
        title: 'Baixa resolução gráfica!',
        text: 'O seu terminal não tem a resolução ideal para sem limitações. Se ainda assim pretender continuar clique em "Aceitar".',
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
        $.fn.popup(options, acceptClick);
    }

    function acceptClick(accepted)
    {
        if (accepted) {
            session.setItem('small-screen', 'accept');
        }
    }

})();
