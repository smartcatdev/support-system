const common = require('./webpack.config'),
      merge  = require('webpack-merge');

module.exports = merge(common, {
    output: {
        filename: 'bundle.dev.js'
    }
});
