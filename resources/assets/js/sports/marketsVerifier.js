MarketsVerifier = new (function() {
    var markets = [
        //Footbal
        2, //Match result
        122, //Draw no bet
        7202, //Double chance
        9535, //Half-time Odd or Even Total
        7354, //Odd or even total
        6832, //Half-time Result
        7591, //2nd Half Result
        9536, //Half-time First Team To Score
        295, //First Team To Score
        6911, //Last Team to Score
        25, //Highest scoring half
        8480, //Home Team Clean Sheet
        8481, //Away Team Clean Sheet
        169, //To Win to Nil
        10459, //Half-time Both Teams To Score
        7079, //Both Teams To Score
        7086, //Home Team Total Goals
        7087, //Away Team Total Goals
        362, //Half-time Total Goals (Bands)
        10849, //Half-time Away Team Total Goals Over/Under
        10850, //Half-time Home Team Total Goals Over/Under
        10887, //Away Team Odd Or Even Goals
        10888, //Home Team Odd Or Even Goals
        7076, //Half-time Totals Over/Under
        253, //Total Goals
        9498, //Home Team Total Goals Over/Under
        259, //Over/Under
        105, //Handicap
        91, //Correct Score
        170, //Half-time Correct Score
        7809, //2nd Half Correct Score
        12, //First Goalscorer
        13, //Last Goalscorer
        14, //Anytime Goalscorer
        42, //To score a Hat-trick

        //Tenis
        322, //Match result
        77, //First Set Winner
        6599, //Second Set Winner
        8660, //Set Handicap
        120, //Set Betting
        7172, //First Set Correct Score
        7530, //Second Set Correct Score

        //Basketball
        306, //Match result
        5, //Half Time Result
        6739, //First Team to Score
        6606, //1st Half Odd or Even Total Points
        7351, //Odd or even total
        193, //Handicap Result
        6950, //1st Quarter Result
        6859, //1st Half Handicap
        6736, //1st Quarter Total Points
        6851, //2nd Quarter Total Points
        6957, //Half-time/Full-time
        286, //Total Points
        147, //Point Spread

        //Handball
        6662, //Match result
        6690, //Draw no bet
        7290, //Double chance
        7024, //Total Goals Odd or Even
        7381, //Half-time Result
        8377, //2nd Half Result
        7307, //Half-time/Full-time
        6799, //Half-time Handicap
        194, //Handicap Result
        9784, //Handicap Result (With Draw)
        6855, //Half-time Total Goals Over/Under
        6663, //Total Goals Over/Under

        //Futsal
        7469, //Match result
        8731, //Draw no bet
        8733, //Odd or even total
        7471, //Handicap
        7772, //Double Chance

        //Rugby Union
        15, //Match result

        //Rugby League
        8133, //Match result

        //Volleyball
        6734, //Match Result
        6777, //Set Betting
        6779, //First Set Winner
        7012, //Second Set Winner
        7013, //Third Set Winner
        8576 //Fourth Set Winner
    ];


    this.checkMarket = function (marketId) {
        if ($.inArray(marketId, markets) === -1) {
            console.log("Market:" + marketId + " not implemented.")
        }
    }
})();
