var Search = new (function ()
{
    init();

    function init()
    {

        $("#textSearch").change(queryChange);
    }

    function queryChange()
    {
        var query = $(this).val();

        if (query.length && (query.length < 3))  {
            alert("A pequisa necessita de pelo menos 3 caracteres.");

            return;
        }

        page("/pesquisa/" + $(this).val());

    }
})();
