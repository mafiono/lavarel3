var MarketsController = new (function() {
    var showGamesMarket = new EventHandler();
    var showGameMarkets = new EventHandler();

    this.showGamesMarket = function(competitionId) {
        showGamesMarket.exec(competitionId);
    };

    this.onShowGamesMarket = function(handler) {
        showGamesMarket.addHandler(handler);
    };

    this.offShowGamesMarket = function(handler) {
        showGamesMarket.removeHandler(handler);
    };

    this.showGameMarkets = function(gameId) {
        showGameMarkets.exec(gameId);
    };

    this.onShowGameMarkets = function(handler) {
        showGameMarkets.addHandler(handler);
    };

    this.offShowGameMarkets = function(handler) {
        showGameMarkets.removeHandler(handler);
    };
})();

$(function() {
    BetsService.addHandler(handleMarketsHeader);
    BetsService.addHandler(handleMarketRequest);
    BetsService.addHandler(handleGameMarketsResponse);

    MarketsController.onShowGameMarkets(function(gameId) {
        var gameContainer = $("#game-container");
        gameContainer.removeClass("hidden");
        $("#markets-container").addClass("hidden");
        if (gameId != null)
            requestGameMarkets(gameId);

    });

    MarketsController.onShowGamesMarket(function(competitionId) {
        var marketsContainer = $("#markets-container");
        $("#game-container").addClass("hidden");
        marketsContainer.removeClass("hidden");
        if (competitionId != null)
            requestMarketsHeader(competitionId);
    });

    middleSpinner = document.getElementById('middleSpinner');
    middleSpinnerItem = new Spinner().spin(middleSpinner);

    Handlebars.registerHelper('match_results', function(context, options) {
        var keys = Object.keys(context);
        var ret = options.fn(context[keys[0]]);
        if (keys.length == 2)
            ret += options.inverse();
        ret += options.fn(context[keys[1]]);
        if (keys.length == 3)
            ret += options.fn(context[keys[2]]);
        return ret;
    });

    Handlebars.registerHelper('create_array', function(obj, arrayName) {
        this[arrayName] = [];
        for (var key in obj) {
            obj["i"+this[arrayName].length] = obj[key];
            this[arrayName].push(obj[key]);
        }
    });

    Handlebars.registerHelper('make_index', function(obj) {
        var i=0;
        for (var key in obj) {
            obj["i"+i++] = obj[key];
        }
    });

    Handlebars.registerHelper("today_date", function() {
        return moment().unix();
    });

    Handlebars.registerHelper("format_date", function(date, format) {
        return moment.unix(date).format(format);
    });

    Handlebars.registerHelper('mod', function(x, n) {
        return x%n;
    });

    Handlebars.registerHelper('get_markets', function(markets, options) {
        var marketsList = {};
        for (var marketKey in markets)
            marketsList[markets[marketKey].type] = {"type": markets[marketKey].type, "name": markets[marketKey].name};
        var ret = "";
        for (var marketKey in marketsList)
            ret += options.fn(marketsList[marketKey]);
        return ret;
    });

    Handlebars.registerHelper("games_date", function(date, options) {
        if (!options.data.root._games_date || options.data.root._games_date!=date) {
            options.data.root._games_date = date;
            return options.fn(this);
        }
    });

    Handlebars.registerHelper("is_favorite", function(gameId, options) {
        if (Favorites.has(gameId))
            return options.fn(this);
    });

    function newBet() {
        var eventId= $(this).data("event-id");
        var eventName = $(this).data("event-name");
        var eventOdds = $(this).data("event-price");
        var marketId = $(this).data("market-id");
        var marketName = $(this).data("market-name");
        var gameName = $(this).data("team1-name")+" - "+$(this).data("team2-name");

        if (BetSlip.has(eventId)) {
            $(this).removeClass("markets-button-selected");
            BetSlip.remove(eventId);
        } else if (BetSlip.betMode() != "multi" || !BetSlip.has(marketId,"marketId")) {
            BetSlip.add(new Bet(eventId, eventName, eventOdds, 0, marketId, marketName, gameName));
            $(this).addClass("markets-button-selected");
        }
    }

    function requestMarketsHeader(competitionId) {
        $("#markets-content").html('<p style="position: relative; margin-top: 180px;" id="middleSpinner"> </p>');
        middleSpinner = document.getElementById('middleSpinner');
        middleSpinnerItem = new Spinner().spin(middleSpinner);

        var params = {
            "command" : "get",
            "rid": "get_markets_header",
            "params" : {
                "source" : "betting",
                "what" : {"sport" : ["name"], "region" : ["name"], "competition" : ["name"], "game" : ["name"], "market" : []}, //"name", "type"
                //"what" : {"sport" : ["alias"], "region" : ["name"], "competition" : [], "game" : [], "market" : [], "event" : []},
                "where" : {
                    "competition" : {"id" : competitionId },
                    "game": {"start_ts" : {"@lt" : moment().add($("#events-period-select").val()*1, 'h').unix()}},
                    "market" : {
                        "@or": [
                            {"type": "1stHalfGoalsW2"},
                            {"type": "1stHalfGoalsW1"},
                            {"type": "FirstHalfTotal"},
                            {"type": "Team2Total"},
                            {"type": "Team1Total"},

                            {"type": "TeamTwoWillWinInBothHalves"},
                            {"type": "TeamOneWillWinInBothHalves"},
                            {"type": "TeamTwoWillScoreInBothHalves"},
                            {"type": "TeamOneWillScoreInBothHalves"},
                            {"type": "SecondHalfBothTeamsScore"},
                            {"type": "HalfTimeBothTeamsToScore"},
                            {"type": "TeamTwoToWinToNil"},
                            {"type": "TeamOneToWinToNil"},
                            {"type": "SecondHalfTeamTwoWillScore"},
                            {"type": "SecondHalfTeamOneWillScore"},
                            {"type": "GoalsInBothHalves"},
                            {"type": "GoalInSecondHalf"},
                            {"type": "GoalInFirstHalf"},
                            {"type": "BothTeamsWillScore"},
                            {"type": "TeamTwoWillScore"},
                            {"type": "TeamOneWillScore"},

                            {"type": "Total"},

                            {"type": "SecondHalfHandicap"},
                            {"type": "FirstHalfHandicap"},
                            {"type": "Handicap"},

                            {"type": "HalfTimeFullTimeDoubleChance"},
                            {"type": "HT-FT"},
                            {"type": "FirstHalfScore"},
                            {"type": "CorrectScore"},

                            {"type": "1Half1X12X2"},
                            {"type": "1X12X2"},

                            {"type": "Qualify"},
                            {"type": "1stGoal"},
                            {"type": "DrawNoBet"},
                            {"type" : "P1P2"},

                            {"type": "2HalfP1XP2"},
                            {"type": "1HalfP1XP2"},
                            {"type": "P1XP2"}
                        ]
                    }
                },
                "subscribe" : false
            }
        };
        BetsService.request(params);
    }

    function handleMarketsHeader(response) {
        if (response.rid == "get_markets_header" && response.code == 0) {
            Template.get("/assets/portal/templates/markets_header.html", function(template) {
                $("#markets-header-container").html(template(response.data.data));
                var marketsSelect = $("#markets-select");
                requestMarket(marketsSelect.find("option:selected").val());
                marketsSelect.change(function() {
                    requestMarket($(this).find("option:selected").val());
                });
            });
        }
    }

    function requestMarket(marketType) {
        var params = {
            "command" : "get",
            "rid": "get_market_" + marketType,
            "params" : {
                "source" : "betting",
                "what" : {"sport" : ["name"], "region" : ["name"], "competition" : [], "game" : [], "market" : [], "event" : []},
                "where" : {
                    "competition" : {"id" : competitionId},
                    "game": {"start_ts" : {"@lt" : moment().add($("#events-period-select").val()*1, 'h').unix()}},
                    "market" : {"type": marketType}
                },
                "subscribe" : false
            }
        };
        $("#markets-content").html('<p style="position: relative; margin-top: 180px;" id="middleSpinner"> </p>');
        var middleSpinner = document.getElementById('middleSpinner');
        middleSpinnerItem = new Spinner().spin(middleSpinner);
        BetsService.request(params);
    }

    function requestGameMarkets(gameId) {
        var params = {
            "command" : "get",
            "rid": "get_game_markets",
            "params" : {
                "source" : "betting",
                "what" : {"sport" : ["name"], "region" : ["name"], "competition" : ["name"], "game" : [], "market" : [], "event" : []},
                "where" : {
                    "game": {"id": gameId},
                    "market" : {
                        "@or": [
                            {"type": "P1XP2"},
                            {"type": "1X12X2"},
                            {"type": "Handicap"},
                            {"type": "Total"}
                        ]
                    }
                },
                "subscribe" : false
            }
        };
        BetsService.request(params);
    }

    function handleGameMarketsResponse(response) {
        if (response.rid != "get_game_markets" || response.code != 0)
            return;
        var data = response.data.data.sport;
        data = data[Object.keys(data)[0]];
        var sportName = data.name;
        data = data.region;
        data = data[Object.keys(data)[0]].competition;
        data = data[Object.keys(data)[0]];
        data["sportName"] = sportName;
        //TODO: apply levelsToArray to sport
        levelsToArray(data,"game",{"game":true,"market":true,"event":true});
        var markets = data.game[0].market;
        for (var i in markets) {
            switch (markets[i].type) {
                case "P1XP2":
                    data["P1XP2"] = markets[i];
                    break;
                case "1X12X2":
                    data["1X12X2"] = markets[i];
                    break;
                case "Handicap":
                    if (!data["Handicap"])
                        data["Handicap"] = [];
                    data["Handicap"].push(markets[i]);
                    break;
                case "Total":
                    if (!data["Total"])
                        data["Total"] = [];
                    data["Total"].push(markets[i]);
                    break;
            }
        }

        Template.get("/assets/portal/templates/game_markets.html", function (template) {
            var gameContainer = $("#game-container");
            gameContainer.html(template(data));
            gameContainer.find("[data-type='odds']").click(newBet);
            $("#markets-game-hide").click(function() {
                MarketsController.showGamesMarket();
            });
        });
    }

    function levelsToArray(data, levelKey, levels) {
        var newArray = [];
        for (var key in data[levelKey]) {
            if (typeof(data[levelKey][key]) === "object")
                levelsToArray(data[levelKey], key, levels);
            if (levels[levelKey])
                newArray.push(data[levelKey][key]);
        }
        if (levels[levelKey])
            data[levelKey] = newArray;
    }

    function handleMarketRequest(response) {
        if (response.code == 0) {
            var template = marketTemplate(response.rid);
            if (template) {
                Template.get("/assets/portal/templates/"+template, function (template) {
                    var data = response.data.data.sport;
                    data = data[Object.keys(data)[0]];
                    var sportName = data.name;
                    data = data.region;
                    data = data[Object.keys(data)[0]].competition;
                    data = data[Object.keys(data)[0]];
                    data["sportName"] = sportName;
                    levelsToArray(data,"game",{"game":true,"market":true,"event":true});
                    data.game.sort(function (a, b) {return a.start_ts-b.start_ts});
                    var marketsContent = $("#markets-content");
                    marketsContent.html(template(data));
                    marketsContent.find("[data-type='game']").click(gameClick);
                    marketsContent.find("[data-type='favorite']").click(favoriteClick);
                    marketsContent.find("[data-type='odds']").click(newBet);
                });
            }
        }
    }

    function gameClick() {
        MarketsController.showGameMarkets($(this).data("game-id"));
    }

    function favoriteClick(e) {
        Favorites.toggleGame($(this).data("game-id"), $(this).data("game-name"), $(this).data("game-date"), $(this).data("game-sport"));
        $(this).toggleClass($(this).data("selected-css"));
        e.stopImmediatePropagation();
        return false;
    }

    function marketTemplate(responseRid) {
        var template = "";
        switch (responseRid) {
            case "get_market_P1XP2":
            case "get_market_1HalfP1XP2":
            case "get_market_2HalfP1XP2":
                template = "market_P1XP2.html";
                break;
            case "get_market_P1P2":
            case "get_market_DrawNoBet":
            case "get_market_1stGoal":
            case "get_market_Qualify":
                template = "market_P1P2.html";
                break;
            case "get_market_1X12X2":
            case "get_market_1Half1X12X2":
                template = "market_1X12X2.html";
                break;
            case "get_market_CorrectScore":
            case "get_market_FirstHalfScore":
            case "get_market_HT-FT":
            case "get_market_HalfTimeFullTimeDoubleChance":
                template = "market_CorrectScore.html";
                break;
            case "get_market_Handicap":
            case "get_market_FirstHalfHandicap":
            case "get_market_SecondHalfHandicap":
                template = "market_Handicap.html";
                break;
            case "get_market_Total":
                template = "market_Total.html";
                break;
            case "get_market_TeamOneWillScore":
            case "get_market_TeamTwoWillScore":
            case "get_market_BothTeamsWillScore":
            case "get_market_GoalInFirstHalf":
            case "get_market_GoalInSecondHalf":
            case "get_market_GoalsInBothHalves":
            case "get_market_SecondHalfTeamOneWillScore":
            case "get_market_SecondHalfTeamTwoWillScore":
            case "get_market_TeamOneToWinToNil":
            case "get_market_TeamTwoToWinToNil":
            case "get_market_HalfTimeBothTeamsToScore":
            case "get_market_SecondHalfBothTeamsScore":
            case "get_market_TeamOneWillScoreInBothHalves":
            case "get_market_TeamTwoWillScoreInBothHalves":
            case "get_market_TeamOneWillWinInBothHalves":
            case "get_market_TeamTwoWillWinInBothHalves":
                template = "market_YesNo.html";
                break;
            case "get_market_Team1Total":
            case "get_market_Team2Total":
            case "get_market_FirstHalfTotal":
            case "get_market_1stHalfGoalsW1":
            case "get_market_1stHalfGoalsW2":
                template = "market_TeamTotal.html";
                break;
        }
        return template;
    }

});
