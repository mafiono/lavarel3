Handlebars.registerHelper('if_eq', function(a, b, opts) {
    if(a == b)
        return opts.fn(this);

    return opts.inverse(this);
});

Handlebars.registerHelper('if_not', function(a, opts) {
    if(!a)
        return opts.fn(this);

    return opts.inverse(this);
});

Handlebars.registerHelper('if_in', function(a, b, opts) {
    var items = b.split(',');

    for (var i in items)
        if (items[i] == a)
            return opts.fn(this);

    return opts.inverse(this);
});

Handlebars.registerHelper('multiply', function(a, b) {
    return (a*b).toFixed(2);
});

Handlebars.registerHelper('parity', function(a) {
    return a%2 ? "odd" : "even";
});

Handlebars.registerHelper('mod', function(x, n) {
    return x%n;
});

Handlebars.registerHelper('round', function(x) {
    return Math.round(x);
});

Handlebars.registerHelper('homeTeam', function(gameName) {
   return gameName.split(/ [-v] /)[0];
});

Handlebars.registerHelper('awayTeam', function(gameName) {
    return gameName.split(/ [-v] /)[1];
});

Handlebars.registerHelper('is_inPlay', function(opts) {
    if (this.in_play && moment().utc() >= moment.utc(this.start_time_utc))
        return opts.fn(this);

    return opts.inverse(this);
});
