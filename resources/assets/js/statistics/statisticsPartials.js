Handlebars.registerPartial('statistics', '\
    <div class="statistics">\
        <div class="header">{{name}}&nbsp;\
            <i id="statistics-close" class="cp-cross"></i>\
            <i id="statistics-open" class="cp-stats-bars"></i>\
        </div>\
        <iframe src="https://www.score24.com/statistics3/index.jsp?partner=betportugal&gameId={{id}}" style="width: 100%" height="1800" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>\
        <p>Os textos, os dados estatísticos e os correspondentes quadros e gráficos apresentados são de exclusiva responsabilidade da <a href="http://www.score24.com" target="_blank" class="link" style="color: white; text-decoration: underline;">www.score24.com</a>, que fornece estes conteúdos ao Casino Portugal para inclusão no sistema. Quando verificar alguma inconsistência nos dados apresentados poderá entrar em contacto connosco informando o link e o conteúdo em questão.</p>\
    </div>\
');
