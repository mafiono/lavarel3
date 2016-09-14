var Info = new (function () {

    var defaultTerm = "sobre_nos";

    var term = defaultTerm;

    var terms = {
        "sobre_nos": '/textos/sobre_nos',
        "termos_e_condicoes": '/textos/termos_e_condicoes',
        "contactos": '/textos/contactos',
        "bonus_e_promocoes":  '/textos/bonus_e_promocoes',
        "faq": '/textos/faq',
        "pagamentos": '/textos/pagamentos',
        "politica_privacidade": '/textos/politica_priv',
        "jogo_responsavel": '/textos/jogo_responsavel'
    };

    init();

    function init()
    {
        $("#info-container").html(Template.apply("info"));

        $("#info-close").click(closeClick);

        $("#info-print").click(printClick);
    }

    this.make = function (_term)
    {
        make(_term);
    };

    function make(_term)
    {
        term = (terms[_term] ? _term : defaultTerm);

        select(term);

        fetch();
    }

    function fetch()
    {
        $.get(terms[term]).done(render);
    }

    function render(data)
    {
        var content = "";

        for (var i in data) {
            content = data[i];
            break;
        }

        $("#info-content").html(content);
    }

    function select(term)
    {
        var links = $("#info-container").find(".links-content").find(".link");

        links.removeClass("selected");

        var icons = links.find("i");

        icons.addClass("fa-plus");
        icons.removeClass("fa-caret-down");

        var link = $("#info-" + term);

        link.addClass("selected");

        link.find("i").addClass("fa-caret-down");
    }

    function closeClick()
    {
        page('/');
    }

    function printClick()
    {

        $("#info-content").print({
            addGlobalStyles : false,
            stylesheet : null,
            rejectWindow : true,
            noPrintSelector : ".no-print",
            iframe : true,
            append : null,
            prepend : null
        });
    }
});
