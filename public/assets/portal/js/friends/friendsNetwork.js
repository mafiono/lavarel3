(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/pt_PT/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function() {
    FB.init({
        appId      : '1684207611840844',
        xfbml      : true,
        version    : 'v2.5'
    });
};

$(function() {
    $("#convida_facebook").click(function() {
        FB.ui({
            method: 'send',
            name: 'Convite para jogar',
            link: 'http://casino.ibetup.eu'
        });
    });
});

var rules = {
    emails_list: {
        required: true
    },
    emails_list_message: {
        required: true
    }
};

var messages = {
    emails_list: {
        required: "Preencha os emails dos seus amigos"
    },
    emails_list_message: {
        required: "Preencha a mensagem do email"
    }
};