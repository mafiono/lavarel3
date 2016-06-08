Handlebars.registerPartial('sports_menu',
    '<ul>' +
    '{{#each sports}}' +
    '<li class="level1">' +
    '<div class="menu1-option" data-sport-id="{{id}}">' +
    '<span><i class="i1 fa fa-chevron-down hidden"></i> </span><font class="n1">{{this.name}}</font>' +
    '</div>' +
    '<ul></ul>' +
    '</li>' +
    '{{/each}}' +
    '</ul>'
);

Handlebars.registerPartial('regions_submenu',
    '{{#each regions}}' +
    '<li class="level2"> ' +
    '<div class="menu2-option" data-region-id="{{id}}">' +
    '<span><i class="i2 fa fa-chevron-down hidden"></i> </span><font class="n2">{{this.name}}</font>' +
    '</div>' +
    '<ul></ul>' +
    '</li>' +
    '{{/each}}'
);

Handlebars.registerPartial('competitions_submenu',
    '{{#each competitions}}' +
    '<li class="level3" data-competition-id="{{this.id}}"> ' +
    '<div class="menu3-option">' +
    '<span><i class="i3 fa fa-chevron-right hidden"></i> </span>' +
    '<font class="n3" style="line-height: 150%;">{{this.name}}</font>' +
    '</div>' +
    '</li>' +
    '{{/each}}'
);
