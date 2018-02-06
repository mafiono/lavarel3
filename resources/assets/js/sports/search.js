Search = new (function ()
{
    this.init = function () {
        init();
    };

    function init()
    {
        $("#searchForm").submit(queryChange);
    }

    function queryChange(e)
    {
        e.preventDefault();

        var query = $("#textSearch").val();

        if (query.length && (query.length < 3))  {
            $.fn.popup({
                type: 'warning',
                title: 'Atenção',
                text: "A pequisa necessita de pelo menos 3 caracteres."
            });

            return false;
        }

        //
        // $("#form-register").show();
        // $("#form-auth").removeClass('col-xs-6').toggleClass('col-xs-3', true);
        // $("#form-login").removeClass('col-xs-12').toggleClass('col-xs-6', true);
        // $("#form-search").removeClass('col-xs-1').toggleClass('col-xs-3', true);
        // $("#btn-pesquisar-class").removeClass('col-xs-6').toggleClass('col-xs-11', true);


        //$('#btn-search').parent().removeClass('col-xs-4').toggleClass('col-xs-2', true);
        $('.nav-ontop input').hide();

        page('/pesquisa/' + query);
    }
})();
