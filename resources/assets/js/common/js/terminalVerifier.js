(function() {
    let session = sessionStorage;
    let options = {
        type: 'warning',
        title: 'Baixa resolução gráfica!',
        text: 'O seu terminal não tem a resolução ideal para sem limitações. Se ainda assim pretender continuar clique em "Aceitar".',
    };

    verify();

    function verify()
    {
        let accept = session.getItem('small-screen');

        if (window.innerWidth < 1200 && accept !== 'accept') {
            $.fn.popup(options, acceptClick);

            return;
        }

        showSite();
    }

    function acceptClick(accepted)
    {
        if (accepted) {
            showSite();
            session.setItem('small-screen', 'accept');
        }
    }

    function showSite() {
        $("body").removeClass("body-mobile");
    }
})();
