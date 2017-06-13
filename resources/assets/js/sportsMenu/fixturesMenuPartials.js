Handlebars.registerPartial('fixtures_menu','\
    {{#each fixtures}}\
        <div class="fixture" data-sport-id="{{../sportId}}" data-sport-name="{{../sportName}}" data-region-id="{{../regionId}}" data-region-name="{{../regionName}}" data-game-id="{{id}}" data-game-name="{{name}}" data-type="fixtureMenu">\
            <div class="favorite">{{> favorite}}</div>\
            <div class="game" data-game-id="{{id}}" data-mode="live" title="{{name}}">{{name}}</div>\
            <div class="matchState">{{elapsed}}<br>{{score}}</div>\
        </div>\
    {{/each}}\
');
