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
                type: 'error',
                title: 'Erro',
                text: "A pequisa necessita de pelo menos 3 caracteres."
            });

            return false;
        }

        $('#form-login').removeClass('col-xs-2').toggleClass('col-xs-4', true);
        $('#btn-search').parent().removeClass('col-xs-4').toggleClass('col-xs-2', true);
        $('.nav-ontop input').hide();

        page('/pesquisa/' + query);
    }
})();
