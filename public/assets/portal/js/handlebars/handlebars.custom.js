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

Handlebars.registerHelper('homeTeam', function(teamName) {
    return teamName.split(/ -|v /)[0];
});

Handlebars.registerHelper('awayTeam', function(teamName) {
    return teamName.split(/ -|v /)[1];
});

