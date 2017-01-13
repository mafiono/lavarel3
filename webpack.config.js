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
    }
};