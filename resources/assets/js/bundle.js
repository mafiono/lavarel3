import "babel-polyfill";

window.$ = window.jQuery = require('./external/jquery.min');
require('./external/viewportchecker');
require('./globals/navbar');

window.page = require('./external/router/page');

require('./external/plugins/jQuery.print');

window.Handlebars = require('./external/handlebars/handlebars.min');
require('./external/handlebars/handlebars.custom');

window.Spinner = require('./external/spin.min.js');
window.moment = require('./external/moment/moment.js');
require('./external/moment/locale/pt.js');

require('./external/template.js');


require('./external/jquery-ui');
require('./external/datepicker-pt');
require('./external/plugins/jquery.slimscroll');

require('./external/plugins/animate');
require('./external/plugins/jquery.form.min');
require('./external/plugins/jquery.validate');
require('./external/plugins/jquery.validate-additional-methods');
window.swal = require('./external/plugins/sweetalert.min');

require('./common/js/MobileHelper');