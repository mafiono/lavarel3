'use strict';

const path  = require('path');

module.exports = {
    // devtool: 'source-map',
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