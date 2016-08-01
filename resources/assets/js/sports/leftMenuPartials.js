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

Handlebars.registerPartial('highlights_submenu','\
    {{#each competitions}}\
        <div class="sportsMenu-box-highlights-submenu" data-competition-id="{{id}}" data-competition-name="{{name}}" data-type="highlight">{{name}}</div>\
    {{/each}}\
');