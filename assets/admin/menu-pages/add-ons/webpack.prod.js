const common  = require('./webpack.config'),
      merge   = require('webpack-merge'),
      Minify  = require('babel-minify-webpack-plugin');

module.exports = merge(common, {
    output: {
        filename: 'bundle.production.min.js'
    },

    plugins: [
        new Minify()
    ]
});
