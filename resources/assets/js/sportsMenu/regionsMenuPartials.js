Handlebars.registerPartial('regions_menu','\
    {{#each regions}}\
        <div> \
            <div class="region" data-sport-id="{{../sportId}}" data-sport-name="{{../sportName}}" data-region-id="{{id}}" data-region-name="{{name}}" data-type="regionMenu">\
                <span class="count">{{#if ../live}}{{fixtures_count}}{{else}}{{competition_count}}{{/if}}</span>\
                <i class="fa fa-caret-down hidden"></i>\
                <span>{{this.name}}</span>\
            </div>\
            <div></div>\
        </div>\
    {{/each}}\
');

