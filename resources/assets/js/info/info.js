var Info = new (function () {

    var term = "sobre_nos";

    var terms = {
        "sobre_nos": '/textos/sobre_nos',
        "termos_e_condicoes": '/textos/termos_e_condicoes'
    };

    init();

    function init()
    {
        $("#info-container").html(Template.apply("info"));

        $("#info-close").click(closeClick);
    }

    this.make = function (_term)
    {
        make(_term);
    };

    function make(_term)
    {
        term = _term;

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

        $("#info-container").find(".links-content").find(".link").removeClass("selected");

        $("#info-" + term).addClass("selected");

        $("#info-content").html(content);
    }

    function closeClick()
    {
        page('/');
    }

});
