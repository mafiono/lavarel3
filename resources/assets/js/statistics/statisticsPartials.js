Handlebars.registerPartial('statistics', '\
    <div class="statistics">\
        <div class="header">{{name}}&nbsp;\
            <i id="statistics-close" class="fa fa-times"></i>\
            <i id="statistics-open" class="fa fa-bar-chart"></i>\
        </div>\
        <iframe src="https://www.score24.com/statistics3/index.jsp?partner=betportugal&eventId={{id}}" style="width: 100%" height="1800" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>\
        <p>A informação estatística disponibilizada no site deve ser usada somente como uma guia. A Casino Portugal não se responsabiliza por eventuais erros nos dados estatísticos fornecidos pela score24. </p>\
    </div>\
');
