Search = new (function ()
{
    init();

    function init()
    {

        $("#searchForm").submit(queryChange);
    }

    function queryChange(e)
    {
        e.preventDefault();

        var query = $("#textSearch").val();

        if (query.length && (query.length < 3))  {
            alert("A pequisa necessita de pelo menos 3 caracteres.");

            return false;
        }

        page('/pesquisa/' + query);
    }
})();
