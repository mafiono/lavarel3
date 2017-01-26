'use strict';

const path  = require('path');

module.exports = {
    module: {
        loaders: [
            {
                test: /\.html$/,
                loader: 'raw',
            }
        ]
    },
    externals: {
        // require("jquery") is external and available
        //  on the global var jQuery
        "jquery": "jQuery"
    }
};