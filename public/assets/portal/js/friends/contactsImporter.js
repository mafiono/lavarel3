

$(function() {
    $("#import_gmail_contacts").click(auth);

    function auth() {
        var config = {
            'client_id': '538341240931-f7r17bvq7q86588ce821srfhm3sdipsr.apps.googleusercontent.com',
            'scope': 'https://www.google.com/m8/feeds'
        };
        gapi.auth.authorize(config, function() {
            fetch(gapi.auth.getToken());

        });
    }

    function fetch(token) {
        $.ajax({
            url: "https://www.google.com/m8/feeds/contacts/default/full?access_token=" + token.access_token + "&alt=json&max-results=500&v=3.0",
            dataType: "jsonp",
            success:function(data) {
                console.log(data);
            }
        });
    }


    WL.init({
        client_id: "000000004C188F0A",
        redirect_uri: "http://casino.ibetup.eu/amigos/convites",
        scope: ["wl.basic", "wl.contacts_emails"],
        response_type: "token"
    });

    $('#import_live_contacts').click(function(e) {
        e.preventDefault();
        WL.login({
            scope: ["wl.basic", "wl.contacts_emails"]
        }).then(function (response)
            {
                WL.api({
                    path: "me/contacts",
                    method: "GET"
                }).then(
                    function (response) {
                        //your response data with contacts
                        console.log(response.data);
                    },
                    function (responseFailed) {
                        //console.log(responseFailed);
                    }
                );

            },
            function (responseFailed)
            {
                //console.log("Error signing in: " + responseFailed.error_description);
            });
    });

});
