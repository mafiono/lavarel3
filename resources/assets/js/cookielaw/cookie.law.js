Handlebars.registerPartial('cookielaw', require('./cookielaw.html'));
CookieLaw = new function () {
    var current = Cookies.getJSON('cookieconsent')||null;
    if (current === null && current !== 'allow') {
        var tmp = $(Template.apply('cookielaw'));
        tmp.find('button').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            Cookies.set('cookieconsent', 'allow', {expires:30});
            tmp.remove();
        });
        $('body').append(tmp);

    }
};