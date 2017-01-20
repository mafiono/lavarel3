window.$ = window.jQuery = require('./external/jquery.min');
require('./external/viewportchecker');
window.Rx = require('./external/rx.umd.min');
require('./globals/navbar');

window.page = require('./external/router/page');

require('./external/plugins/jQuery.print');

window.Handlebars = require('./external/handlebars/handlebars.min');
require('./external/handlebars/handlebars.custom');

window.Spinner = require('./external/spin.min.js');
window.moment = require('./external/moment/moment.js');
require('./external/moment/locale/pt.js');
window.Cookies = require('./external/js-cookie/js.cookie.min.js');
require('./external/template.js');