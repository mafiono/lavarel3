//TODO: there's still a lot of old code, refactor is in need!

var competitionId;

var SportsBarController = new (function() {
    var gameType = windows.location.pathname==="/aovivo"?1:0;
    this.gameType = function(gType) {
        if (gType!==null && gType!=gameType) {
            gameType = gType;
            requestSports(gType);
        } else
            return gameType;
    }
})();

//function onLevel3Click() {
//    middle.html('<p style="position: relative; margin-top:200px;" id="middleSpinner"> </p>');
//    middleSpinner = document.getElementById('middleSpinner');
//    middleSpinnerItem = new Spinner().spin(middleSpinner);
//
//    var competitionId = $(this).data('id');
//    if (competitionId == null || competitionId.length == 0)
//        return false;
//
//    var params = {
//        "command" : "get",
//        "rid": "get_scoreboard",
//        "params" : {
//            "source" : "betting",
//            "what" : {"sport" : ["alias"], "region" : ["name"], "competition" : [], "game" : [], "market" : [], "event" : []},
//            //"what" : {"market" : ["name"]},
//            "where" : {
//                "competition" : {"id" : competitionId},
//                "game": {"start_ts" : {"@lt" : moment().add($("#events-period-select").val()*1, 'h').unix()}},
//                "market" : {
//                    "@or": [
//                        {"type": "P1XP2"},
//                        {"type" : "P1P2"}
//                    ]
//                }
//            },
//            "subscribe" : false
//        }
//    };
//    BetsService.request(params);
//}

function enableMiddle() {
    middleMarket = $('.middleMarket');
    marketSelect = $('.marketSelect');
    simpleBets = $('#simpleBets');
    multipleBets = $('#multipleBets');
    eventBet = $('.eventBet');
    defaultBet = $('.defaultBet:first');
    totalBets = $('#totalBets');
    eventBet.on('click', onEventBetClick);
    marketSelect.on('change', onMarketSelectChange);
    middleSpinner = document.getElementById('middleSpinner');
}

function requestSports(gameType) {
    switchEventType = gameType;
    var params = {
        "command" : "get",
        "rid":"get_sports",
        "params" : {
            "source" : "betting",
            "what" : {"sport" : [], "region" : [], "competition" : []},
            "where" : {
                "sport" : {
                    "@or" : [{"id": 844}, {"id": 848}, {"id": 850}, {"id": 890}]
                },
                //"region" : {
                //    "@or" : [
                //        {"id": 65541},  //Spain
                //        {"id": 65546},  //France
                //        {"id": 65571},  //Germany
                //        {"id": 65545},  //Italy
                //        {"id": 65598},  //NetherLands
                //        {"id": 65547},  //Turkey
                //        {"id": 65549},  //Russia
                //        {"id": 65552},  //Argentina
                //        {"id": 65558},  //Australia
                //        {"id": 65576},  //Brazil
                //        {"id": 65578},  //Scotland
                //        {"id": 65547},  //CzechRepublic
                //        {"id": 65583},  //Switzerland
                //        {"id": 65587},  //Belgium
                //        {"id": 65589},  //Denmark
                //        {"id": 65547},  //Turkey
                //        {"id": 65590},  //Mexico
                //        {"id": 65595},  //Austria
                //        {"id": 65596},  //Latvia
                //        {"id": 65597},  //Greece
                //        {"id": 65606},  //SaudiArabia
                //        {"id": 65613},  //Wales
                //        //{"id": xxx},  //US
                //        {"id": 65597}  //Greece
                //        //{"id": xxx},  //Romania
                //        //{"id": xxx},  //Ukraine
                //        //{"id": xxx},  //Sweden
                //        //{"id": xxx},  //Hungary
                //        //{"id": xxx},  //Croatia
                //    ]
                //},
                "game" : {"type" : gameType},
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
            }
        },
        "subscribe" : false
    };
    if (gameType == 0) {
        var gameFilter = {
            "@and" : [
                {"type" : gameType},
                {"start_ts" : {"@lt" : moment().add($("#events-period-select").val()*1, 'h').unix()}}
            ]
        };
        params.params.where["game"] = gameFilter;
        $("#events-period-select").show();
    } else
        $("#events-period-select").hide();

    BetsService.request(params);
}

function handleSportsResponse(response) {
    if (response.rid == "get_sports" && response.code == 0)
        populateSportsMenu(response.data.data);
}

function populateSportsMenu(data) {
    Template.get("/assets/portal/templates/left_menu.html", function (template) {
        $("#left-menu-container").html(template(data));
        $(".menu1-option").click(function () {
            $(this).parent().find(".level2").toggleClass("hidden");
            $(this).find(".i1").toggleClass("hidden");
            $(this).find(".n1").toggleClass("menu-option-selected");
        });

        $(".menu2-option").click(function () {
            $(this).parent().find(".level3").toggleClass("hidden");
            $(this).find(".i2").toggleClass("hidden");
            $(this).find(".n2").toggleClass("menu-option-selected");
        });

        $(".menu3-option").click(function () {
            $(".n3").removeClass("menu-option-selected");
            $(".i3").addClass("hidden");
            $(this).find(".i3").removeClass("hidden");
            $(this).find(".n3").addClass("menu-option-selected");
            competitionId = $(this).parent().data('id');
            MarketsController.showGamesMarket($(this).parent().data('id'));
        });

        setTimeout(function() {
            var soccer = $("#level1-844");
            soccer.click();
            var regions = soccer.parent().find(".menu2-option");
            var randRegion = $(regions[Math.floor(Math.random()*regions.length)]);
            randRegion.click();
            var competitions = randRegion.parent().find(".menu3-option");
            var randCompetition = competitions[Math.floor(Math.random()*competitions.length)];
            randCompetition.click();
        },1000);
        enableLeftBar(switchEventType);
    });
}

//Old code
function enableLeftBar(type) {
    preMatch = $('#preMatch');
    live = $('#live');
    if (type == 0) {
        preMatch.addClass('blue-active');
        live.removeClass('blue-active');
    }else if(type == 1) {
        preMatch.removeClass('blue-active');
        live.addClass('blue-active');
    }
    preMatch.off().on('click', function(){
        //switchMode(0);
        //requestSports(0);
        Router.navigate("/desportos");
        return false;
    });
    live.off().on('click', function(){
        //switchMode(1)
        //requestSports(1);
        Router.navigate("/aovivo");
        return false;
    });
    //level3 = $('.level3');
    //level3.off().on('click', onLevel3Click);
    return false;
}

$(function () {
    BetsService.addHandler(handleSportsResponse);
});
