Info = new (function () {

    var defaultTerm = "sobre_nos";

    var term = defaultTerm;

    var goBack = "";

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

    this.make = function (_term, back)
    {
        make(_term, back);
    };

    function make(_term, back)
    {
        term = (terms[_term] ? _term : defaultTerm);

        var back = String(back + "").split('=');
        goBack = back[back.length-1] || "";

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

        icons.addClass("cp-plus");
        icons.removeClass("cp-caret-down");

        var link = $("#info-" + term);

        link.addClass("selected");

        var icon = link.find("i");

        icon.addClass("cp-caret-down");
        icon.removeClass("cp-plus");
    }

    function closeClick()
    {
        if (goBack !== "")
            page(goBack);
        else
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
