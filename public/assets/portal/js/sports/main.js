function requestSession() {
    var request = {"command":"request_session","rid":"request_session","params": {"site_id":234,"language":"por_2"}};
    BetsService.request(request);
}

function requestLogin() {
    var request = {"command":"restore_login","rid":"restore_login","params": {"auth_token":phpAuthUser.api_password,"user_id":phpAuthUser.id}};
    BetsService.request(request);
}

function requestUserBets() {
    var request = {"command":"bet_history","rid":"bet_history","params": {"where":{"outcome":0}}};
    BetsService.request(request);
}

function requestBalance() {
    var request = {"command":"get_balance","rid":"get_balance"};
    BetsService.request(request);
}

function handleSessionResponse(response) {
    if (response.rid == "request_session" && response.code == 0) {
        sid = response.data.sid;
        console.log('Session ID: '+sid);
        if (phpAuthUser != null) {
            requestLogin();
        }
        requestSports(switchEventType);
    }
}

function handleLoginResponse(response) {
    if (response.rid == "restore_login" && response.code == 0) {
        console.log('Player logged in.');
        setInterval(requestBalance, 30000);
        requestUserBets();
    }
}

function handleUserBetsResponse(response) {
    if (response.rid == "bet_history" && response.code == 0)
        if (response.data.bets.length > 0)
            populateOpenBets(response.data);
}

function handleBalanceResponse(response) {
    if (response.rid == "get_balance")
        if (response.code != 0) {
            //alert("Session expired.");
            //window.location.href = '/logout';
            console.log(response);
        }
}

$(function() {
    var host = "ws://swarm-partner.betconstruct.com";
    BetsService.onConnect(requestSession);
    BetsService.addHandler(handleSessionResponse);
    BetsService.addHandler(handleLoginResponse);
    BetsService.addHandler(handleUserBetsResponse);
    BetsService.addHandler(handleBalanceResponse);

    BetsService.connect(host);

    $(".favoritos-contend").html(Favorites.count());

    //$("a").click(function() {
    //    history.pushState({}, '', $(this).attr("href"));
    //    return false;
    //});
    //
    //window.onpopstate = function() {
    //    alert(window.location.pathname);
    //}
});