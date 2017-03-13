Handlebars.registerPartial('competitions_menu','\
    {{#each competitions}}\
        <div>\
            <div class="competition" data-sports-id="{{../sportId}}" data-sports-name="{{../sportName}}" data-region-id="{{../regionId}}" data-region-name="{{../regionName}}" data-competition-id="{{id}}" data-competition-name="{{name}}" data-type="competitionMenu">\
                <i class="cp-caret-right hidden"></i>\
                <span>{{this.name}}</span>\
            </div>\
        </div>\
    {{/each}}\
');

