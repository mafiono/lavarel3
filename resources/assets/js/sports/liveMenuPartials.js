Handlebars.registerPartial('liveMenu_sports', '\
    <ul>\
        {{#each sports}}\
            <li class="level1">\
                <div class="sport" data-sport-id="{{id}}" data-sport-name="{{name}}">\
                    <i class="fa fa-plus expand"></i>\
                    <i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp; {{this.name}}\
                </div>\
                <ul></ul>\
            </li>\
        {{/each}}\
    </ul>\
');

Handlebars.registerPartial('liveMenu_regions','\
    {{#each regions}}\
        <li class="level2"> \
            <div class="region" data-region-id="{{id}}" data-region-name="{{name}}">\
                <span class="count">{{this.fixtures_count}}</span>\
                <i class="fa fa-caret-down collapse hidden"></i>\
                {{this.name}}\
            </div>\
            <ul></ul>\
        </li>\
    {{/each}}\
');

Handlebars.registerPartial('liveMenu_fixtures','\
    {{#each fixtures}}\
        <li class="level3" data-game-id="{{this.id}}" data-game-name="{{name}}">\
            <table>\
                <tr>\
                    <td class="favorite">{{> favorite}}</td>\
                    <td class="fixture">{{this.name}}</td>\
                </tr>\
            </table>\
        </li>\
    {{/each}}\
');