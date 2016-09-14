Handlebars.registerPartial('match_state', '{{parse_score}}{{elapsed}}\'<br>{{score.home}} - {{score.away}}');

Handlebars.registerHelper('parse_score', function() {
    if (this.score)
        this.score = JSON.parse(this.score).score;

    return "";
});
