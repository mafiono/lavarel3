var Handlebars = require('./handlebars.min');

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

Handlebars.registerHelper('is_selection_valid', function(opts) {
    if (this.trading_status === "Trading" && this.decimal > 1)
        return opts.fn(this);

    return opts.inverse(this);
});

Handlebars.registerHelper('does_not_have_scoreCenter', function(opts) {
    if (!opts.data.root.live || !([10, 4].indexOf(this.sport_id) > -1))
        return opts.fn(this);

    return opts.inverse(this);
});

Handlebars.registerHelper('does_not_have_scoreCenter', function(opts) {
    if (!opts.data.root.live || !([10, 4].indexOf(this.sport_id) > -1))
        return opts.fn(this);

    return opts.inverse(this);
});

Handlebars.registerHelper('sport_icon', function(sportId) {
    let sports = {
        10: 'cp-futebol',
        4: 'cp-basquete',
        12: 'cp-golfe',
        16: 'cp-d-motorizados',
        15: 'cp-hockey-sticks',
        24: 'cp-tenis',
        491393: 'cp-futebol',
        73743: 'cp-raguebi',
        73744: 'cp-raguebi',
        99614: 'cp-andebol',
        91189: 'cp-voleibol'
    };

    return sports[sportId];
});

Handlebars.registerHelper('sport_name', function(sportId) {
    let sports = {
        10: 'Futebol',
        4: 'Basquetebol',
        15: 'Hoquei no Gelo',
        16: 'Desportos Motorizados',
        12: 'Golfe',
        24: 'Tenis',
        491393: 'Futsal',
        73743: 'Rugby League',
        73744: 'Rugby Union',
        99614: 'Andebol',
        91189: 'Voleibol'
    };

    return sports[sportId];
});

Handlebars.registerHelper('debug', function(obj) {
    console.log(obj);

    return '';
});

Handlebars.registerHelper('if_template', function(template, opts) {
    // console.log({
    //     'template' : template,
    //     'opts' : opts,
    //     'this' : this,
    // });

    return new Handlebars.SafeString(Template.apply(template, opts.hash));
});