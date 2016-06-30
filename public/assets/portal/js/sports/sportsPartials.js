Handlebars.registerPartial('sports_menu', '\
    <ul>\
        {{#each sports}}\
            <li class="level1">\
                <div class="menu1-option sportsMenu-box menu sport" data-sport-id="{{id}}" data-sport-name="{{name}}">\
                    <span class="sportsMenu-text sport expand">&nbsp;<i class="i1 fa fa-plus sportsMenu-icon-sport expand"></i></span>\
                    <span class="n1 sportsMenu-text sport"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp; {{this.name}}</span>\
                </div>\
                <ul></ul>\
            </li>\
        {{/each}}\
    </ul>\
');

Handlebars.registerPartial('regions_submenu','\
    {{#each regions}}\
        <li class="level2"> \
            <div class="menu2-option sportsMenu-box menu region" data-region-id="{{id}}" data-region-name="{{name}}">\
                <span class="sportsMenu-text-region count">{{this.competition_count}}</span>\
                <i class="i2 fa fa-caret-down sportsMenu-icon-region-selected hidden"></i>\
                <span class="n2 sportsMenu-text-region">{{this.name}}</span>\
            </div>\
            <ul></ul>\
        </li>\
    {{/each}}\
');

Handlebars.registerPartial('competitions_submenu','\
    {{#each competitions}}\
        <li class="level3" data-competition-id="{{this.id}}" data-competition-name="{{name}}">\
            <div class="menu3-option sportsMenu-box menu competition">\
                <span class="sportsMenu-text competition expand">&nbsp;<i class="i3 fa fa-caret-right hidden"></i></span>\
                <span class="n3 sportsMenu-text competition">{{this.name}}</span>\
            </div>\
        </li>\
    {{/each}}\
');
