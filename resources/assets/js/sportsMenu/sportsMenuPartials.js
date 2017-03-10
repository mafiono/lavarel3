Handlebars.registerPartial('sports_menu', '\
    <div class="sportsMenu">\
        {{#each sports}}\
            <div>\
                <div class="sport" data-sport-id="{{id}}" data-sport-name="{{name}}" data-type="sportMenu">\
                    <i class="cp cp-plus"></i>\
                    <i class="{{sport_icon this.id}}"></i> &nbsp; {{this.name}}\
                </div>\
                <div></div>\
            </div>\
        {{/each}}\
    </div>\
');
