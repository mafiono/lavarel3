Statistics = new (function() {
    var options = {};

    init();

    this.make = function (_options) {
        update(_options);

        make();
    };

    function update(_options)
    {
        for (var i in _options)
            options[i] = _options[i];
    }

    function make (path)
    {
        var container = $("#statistics-container");

        container.html("");

        fetch();
    }

    function fetch()
    {
        $.get(ODDS_SERVER + "fixtures?ids=" +
            options.fixtureId +
            (options.live ? "&live" : "")
        ).done(render);
    }

    function render(data)
    {
        if (!data.fixtures.length)
            return;

        options.name = data.fixtures[0].name;

        options.id = data.fixtures[0].external_id?data.fixtures[0].external_id:0;

        $("#statistics-container").html(Template.apply("statistics", options));

        $("#statistics-close").click(closeClick);

        $("#statistics-open").click(openClick);

        sendMessage();
    }

    function closeClick()
    {
        page.replace(options.closePath);

        page.back("/");
    }

    function openClick()
    {
        var width = 1200;
        var height = 800;

        window.open('https://www.score24.com/statistics3/index.jsp?partner=casinoportugal&gameId=' + options.id,
            'newwindow',
            'width=' + width + ', height=' + height + ', top=' + ((window.outerHeight - height) / 2) + ', left=' + ((window.outerWidth - width) / 2)
        );
    }

    function init() {
        if (window.addEventListener) {  // all browsers except IE before version 9
            window.addEventListener ("message", onMessage, false);
        }
        else {
            if (window.attachEvent) {   // IE before version 9
                window.attachEvent("onmessage", onMessage);
            }
        }
    }

    function onMessage(event) {
        var message = event.data;

        if (typeof(message) !== "string") {
            return;
        }

        var arr = message.split (",");
        // console.log("On message: changing Height to ", arr[1]);
        $("#statistics-container").find('iframe').css({
            'height': arr[1] + 'px'
        });
    }

    function sendMessage() {
        var iframe = $("#statistics-container").find('iframe');
        iframe.css({ 'height': '400px'});
        // send the 'getstate' message to the frame window
        if (iframe.get(0).contentWindow.postMessage) {
            // console.log("Send Message");
            iframe.get(0).contentWindow.postMessage("getstate", "*");
            // iframe.on('load', function () {
            //     console.log("On load");
            // });
        } else {
            // add a reasonable height
            iframe.css({ 'height': '900px'});
        }
    }

})();
