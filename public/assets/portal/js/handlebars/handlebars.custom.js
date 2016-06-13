Handlebars.registerHelper('if_eq', function(a, b, opts) {
    if(a == b)
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
    return (a*b).toFixed(2)*1;
});

    
