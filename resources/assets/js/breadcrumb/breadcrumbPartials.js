Handlebars.registerPartial('breadcrumb', '\
    <div class="breadcrumb">\
        {{#if_in operation "Favoritos,AO-VIVO"}}\
            <span class="selected">{{operation}}</span>\
        {{/if_in}}\
        {{#if_eq operation "Pesquisa"}}\
            {{operation}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{query}}</span>\
        {{/if_eq}}\
        {{#if_eq operation "Destaques"}}\
            {{operation}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_eq}}\
        {{#if_eq operation "Competition"}}\
            {{sport}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            {{region}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_eq}}\
    </div>\
');