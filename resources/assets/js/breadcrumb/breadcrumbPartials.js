Handlebars.registerPartial('breadcrumb', '\
    <div class="breadcrumb">\
        {{#if_in operation "favorites"}}\
            <span class="selected">Favoritos</span>\
        {{/if_in}}\
        {{#if_eq operation "search"}}\
            Pesquisa &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{query}}</span>\
        {{/if_eq}}\
        {{#if_eq operation "highlights"}}\
            Destaques &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_eq}}\
        {{#if_in operation "competition,markets"}}\
            {{sport}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            {{region}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_in}}\
    </div>\
');