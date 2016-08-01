Handlebars.registerPartial('breadcrumb', '\
    <div class="breadcrumb">\
        {{#if_in mode "favorites"}}\
            <span class="selected">Favoritos</span>\
        {{/if_in}}\
        {{#if_eq mode "search"}}\
            Pesquisa &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{query}}</span>\
        {{/if_eq}}\
        {{#if_eq mode "highlights"}}\
            Destaques &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_eq}}\
        {{#if_in mode "competition,markets"}}\
            {{sport}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            {{region}} &nbsp;<i class="fa fa-caret-right"></i>&nbsp; \
            <span class="selected">{{competition}}</span>\
        {{/if_in}}\
    </div>\
');