$(function() {
    $("#import_gmail_contacts").click(auth);

    function auth() {
        layerBlock.show();
        if (!confirm("Tem a certeza que quer enviar um convite aos seus contactos do Gmail?")) {
            layerBlock.hide();
            return;
        }
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
                var emails = data.feed.entry.map(function(elem) {
                    return elem.gd$email?elem.gd$email[0].address:"";
                });
                sendEmailInvites(emails);
            },
            error: function() {
                invitesSentFailure();
            },
            complete: function() {
                layerBlock.hide();
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
        layerBlock.show();
        e.preventDefault();
        if (!confirm("Tem a certeza que quer enviar um convite aos seus contactos do Hotmail/Live?")) {
            layerBlock.hide();
            return;
        }
        WL.login({
            scope: ["wl.basic", "wl.contacts_emails"]
        }).then(function (response) {
            WL.api({
                path: "me/contacts",
                method: "GET"
            }).then(
                function (response) {
                    var emails = response.data.map(function(elem) {
                        return elem.emails.preferred;
                    });
                    sendEmailInvites(emails);
                    layerBlock.hide();
                },
                function (responseFailed) {
                    invitesSentFailure();
                    layerBlock.hide();
                }
            );
        },
        function (responseFailed) {
            invitesSentFailure();
            layerBlock.hide();
        });
    });

    $("#import_yahoo_contacts").click(function () {
        invitesSentFailure();
    });

    function sendEmailInvites(emails) {
        layerBlock.show();
        $.post("/amigos/bulk-invites",{
                "emails_list": JSON.stringify(emails)
            }, invitesSentSuccess)
        .always(function() {
            layerBlock.hide();
        });
    }

    function invitesSentSuccess() {
        alert("Convites enviados.");
    }

    function invitesSentFailure() {
        alert ("Serviço indisponível.")
    }

});
