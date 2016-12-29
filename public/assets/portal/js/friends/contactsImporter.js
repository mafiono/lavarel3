$(function() {
    $(".friends .icons .gmail").click(auth);

    function auth() {
        $.fn.popup({
            title: "Gmail",
            text: "Tem a certeza que quer enviar um convite aos seus contactos do Gmail?",
            showCancelButton: true,
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
        }, function (isConfirm) {
            // layerBlock.hide();
            if (isConfirm) {
                var config = {
                    //'client_id': '538341240931-f7r17bvq7q86588ce821srfhm3sdipsr.apps.googleusercontent.com',
                    'client_id': '298968433284-dfhr1in69vgcu99oulsfnn9qc7mcrb9n.apps.googleusercontent.com',
                    'scope': 'https://www.googleapis.com/auth/gmail.send', // https://www.google.com/m8/feeds',
                    'immediate': true
                };
                gapi.auth.authorize(config, function(authResult) {
                    if (authResult && !authResult.error) {
                        fetch(gapi.auth.getToken());
                    } else {
                        $.fn.popup({
                            title: "Gmail",
                            text: "Ocorreu um erro",
                            type: 'error'
                        });
                    }
                });
            }
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
                // layerBlock.hide();
            }
        });
    }

    WL.init({
        client_id: "000000004C188F0A",
        // redirect_uri: "http://casino.ibetup.eu/amigos/convites",
        scope: ["wl.basic", "wl.contacts_emails"],
        response_type: "token"
    });

    $('.friends .icons .hotmail').click(function(e) {
        // layerBlock.show();
        e.preventDefault();
        if (!confirm("Tem a certeza que quer enviar um convite aos seus contactos do Hotmail/Live?")) {
            // layerBlock.hide();
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
                    // layerBlock.hide();
                },
                function (responseFailed) {
                    invitesSentFailure();
                    // layerBlock.hide();
                }
            );
        },
        function (responseFailed) {
            invitesSentFailure();
            // layerBlock.hide();
        });
    });

    $(".friends .icons .yahoo").click(function () {
        invitesSentFailure();
    });

    function sendEmailInvites(emails) {
        // layerBlock.show();
        $.post("/amigos/bulk-invites",{
                "emails_list": JSON.stringify(emails)
            }, invitesSentSuccess)
        .always(function() {
            // layerBlock.hide();
        });
    }

    function invitesSentSuccess() {
        $.fn.popup({
            'text': 'Convites enviados.'
        });
    }

    function invitesSentFailure() {
        $.fn.popup({
            'text': 'Serviço indisponível.',
            'type': 'error'
        });
    }

});
