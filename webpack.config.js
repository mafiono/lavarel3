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
    vue: {
        loaders: {
            scss: 'vue-style-loader!css-loader!sass-loader'
        }
    },
    externals: {
        // require("jquery") is external and available
        //  on the global var jQuery
        "jquery": "jQuery"
    }
};