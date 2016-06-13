var Template = new (function ()
{
    var templates = {};

    this.get = function(path, callback, data)
    {
        if (templates[path]) {
            callback(templates[path], data);
            return;
        }

        $.get(path, function (templateScript) {
                templates[path] = Handlebars.compile(templateScript);
                callback(templates[path], data);
        });
    };

    this.apply = function(name, data)
    {
        if(!templates[name])
            templates[name] = Handlebars.compile("{{> " + name +"}}");

        return templates[name](data);
    }
})();



