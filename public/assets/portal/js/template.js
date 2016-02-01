var Template = new (function () {
    var templates = {};

    this.get = function(path, callback, data) {
        if (templates[path])
            callback(templates[path], data);
        else
            $.get(path, function (templateScript) {
                templates[path] = Handlebars.compile(templateScript);
                callback(templates[path], data);
        });
    }
})();

// Handlebars helper functions section

Handlebars.registerHelper('precision_2', function(a) {
    return (a*1).toFixed(2)*1;
});

Handlebars.registerHelper('multiply', function(a, b) {
    return (a*b).toFixed(2)*1;
});

Handlebars.registerHelper('if_eq', function(a, b, opts) {
    if(a == b) // Or === depending on your needs
        return opts.fn(this);
    else
        return opts.inverse(this);
});

