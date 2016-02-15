var BetValidator = new (function () {
    var lang = "pt";
    var simpleBetSuccessHandler = doNothing;
    var multiBetSuccessHandler = doNothing;
    var simpleBetErrorHandler = doNothing;
    var multiBetErrorHandler = doNothing;
    var resultErrors = {
        "OK": {
            "en": "Accepted",
            "pt": "Aceite."
        },
        "CHANGE_ODD": {
            "en": "Odd value (price) has been changed",
            "pt": "O valor da cota mudou."
        },
        "EVENT_LOCKED": {
            "en": "Event is locked",
            "pt": "Evento está bloqueado"
        },
        "LIMIT_ERROR": {
            "en": "Event limit exceeded",
            "pt": "O evento exedeu o limite."
        },
        "BASIS_CHANGED": {
            "en": "Event basis has changed",
            "pt": "A base do evento mudou."
        },
        "GAME_STARTED": {
            "en": "Game is already started",
            "pt": "O jogo já começou."
        },
        "ONHOLD": {
            "en": "Accepted, but placed on hold for review",
            "pt": "Aceite, mas foi posto em espera para revisão"
        },
        "500-999": {
            "en": "Forwarded error code from Operator’s PartnerAPI backend",
            "pt": "Erro interno."
        },
        "1000": {
            "en": "Internal Error",
            "pt": "Erro interno."
        },
        "1001": {
            "en": "Null value for argument",
            "pt": "Argumento com valor nulo"
        },
        "1002": {
            "en": "Wrong Login/Password",
            "pt": "Login inválido."
        },
        "1003": {
            "en": "User blocked",
            "pt": "Utilizador bloquedado."
        },
        "1004": {
            "en": "User dismissed",
            "pt": "Utilizador dispensado."
        },
        "1005": {
            "en": "Password error",
            "pt": "Erro de password."
        },
        "1008": {
            "en": "Logging in the page is not possible, since user is not activated or veriﬁed"
        },
        "1009": {
            "en": "Such a veriﬁcation code does not exist."
        },
        "1012": {
            "en": "Incorrect phone number"
        },
        "1013": {
            "en": "Password is too short"
        },
        "1014": {
            "en": "Failed to send veriﬁcation SMS"
        },
        "1099": {
            "en": "Fork exception"
        },
        "1100": {
            "en": "Game is already started",
            "pt": "O jogo já começou."
        },
        "1102": {
            "en": "Game starttime is already past",
            "pt": "O início do jogo foi ultrapassado."
        },
        "1103": {
            "en": "Bet editing time is already past",
            "pt": "O tempo de edição da aposta foi ultrapassado."
        },
        "1104": {
            "en": "Bet is payed 1105 Bet status not ﬁxed"
        },
        "1106": {
            "en": "Bet lose 1107 Bet is online"
        },
        "1108": {
            "en": "Wrong value for coefﬁcient"
        },
        "1109": {
            "en": "Wrong value for amount (in case of system bet - amount is less than minimum allowed)"
        },
        "1112": {
            "en": "Request is already paid!"
        },
        "1113": {
            "en": "Request is already storned!"
        },
        "1117": {
            "en": "Wrong login or E-mail"
        },
        "1118": {
            "en": "Duplicate Login"
        },
        "1119": {
            "en": "Duplicate EMail"
        },
        "1123": {
            "en": "Duplicate doc number"
        },
        "1126": {
            "en": "Bet declined by SKKS"
        },
        "1127": {
            "en": "Duplicate phone number"
        },
        "1150": {
            "en": "You yet are not allowed to bet on the given event yet"
        },
        "1151": {
            "en": "Duplicate Facebook ID"
        },
        "1170": {
            "en": "Card lot blocked"
        },
        "1171": {
            "en": "Scratch card already activated"
        },
        "1172": {
            "en": "Scratch card blocked"
        },
        "1174": {
            "en": "Wrong scratch card currency (not supported for user currency)"
        },
        "1200": {
            "en": "Wrong value exception 1273 Wrong scratch card number"
        },
        "1300": {
            "en": "Double value exception",
            "pt": "As apostas selecionadas não podem ser combinadas."
        },
        "1400": {
            "en": "Double event exception",
            "pt": "As apostas selecionadas não podem ser combinadas."
        },
        "1500": {
            "en": "Limit exception",
            "pt": "O limite foi ultrapassado."
        },
        "1501": {
            "en": "The sum exceeds maximum allowable limit",
            "pt": "O montante excede o limite permitido."
        },
        "1550": {
            "en": "The sum exceeds maximum allowable limit",
            "pt": "O montante excede o limite máximo. "
        },
        "1560": {
            "en": "The sum is less than minimum allowable limit",
            "pt": "O montante mínimo permitido é de 2 euros."
        },
        "1600": {
            "en": "There is going the correction of coefﬁcient.",
            "pt": "Vai existir uma correção de coeficiente."
        },
        "1700": {
            "en": "Wrong access exception"
        },
        "1800": {
            "en": "Odds is changed from %s to %s"
        },
        "1900": {
            "en": "The events can be included only in the express"
        },
        "1910": {
            "en": "The events can be included only in the ordinar"
        },
        "2000": {
            "en": "Odds restriction exception"
        },
        "2100": {
            "en": "Payment restriction exception"
        },
        "2200": {
            "en": "Client limit exception"
        },
        "2300": {
            "en": "Ofﬁce limit exception"
        },
        "2302": {
            "en": "Terminal balance exception"
        },
        "2400": {
            "en": "Client balance is less",
            "pt": "O balanço do cliente é menor."
        },
        "2403": {
            "en": "There are active requests for this client"
        },
        "UNKNOWN": {
            "en": "Unknown error.",
            "pt": "Erro desconhecido."
        }
    };

    this.validate = function(betResponse) {
        if (betResponse.code == 0) {
            var resultCode = betResponse.data.result;
            if (betResponse.data.details && betResponse.data.details.length && betResponse.data.details[0].status != "OK")
                resultCode = betResponse.data.details[0].status;
            var errorMsg = this.errorMsg(resultCode);
            if (resultCode == "OK") {
                if (betResponse.rid == "get_multi_bets")
                    multiBetSuccessHandler(betResponse, errorMsg);
                else
                    simpleBetSuccessHandler(betResponse, errorMsg);
            } else
                if (betResponse.rid == "get_multi_bets")
                    multiBetErrorHandler(betResponse, errorMsg);
                else
                    simpleBetErrorHandler(betResponse, errorMsg);
        }
    };

    this.errorMsg = function(code) {
        if (resultErrors[code]) {
            if (resultErrors[code][lang])
                return resultErrors[code][lang];
            return resultErrors[code]["en"];
        }
        return resultErrors["UNKNOWN"][lang];
    };

    this.language = function(language) {
        lang = language;
    };
    this.onSimpleBetSuccess = function (callback) {
        simpleBetSuccessHandler = callback;
    };
    this.onMultiBetSuccess = function (callback) {
        multiBetSuccessHandler = callback;
    };
    this.onSimpleBetError = function(callback) {
        simpleBetErrorHandler = callback;
    };
    this.onMultiBetError = function(callback) {
        multiBetErrorHandler = callback;
    };
    function doNothing() {}
})();
