Handlebars.registerPartial('cookielaw', require('./cookielaw.html'));
let Cookies = require('../external/js-cookie/js.cookie.min.js');
CookieLaw = new function () {
    let current = Cookies.getJSON('cookieconsent')||null;
    if (current === null && current !== 'allow') {
        let tmp = $(Template.apply('cookielaw'));
        tmp.find('button').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            let expire = new Date();
            expire.setFullYear(expire.getFullYear() + 5);
            Cookies.set('cookieconsent', 'allow', {expires:expire});
            tmp.remove();
        });
        $('body').append(tmp);
    }
};