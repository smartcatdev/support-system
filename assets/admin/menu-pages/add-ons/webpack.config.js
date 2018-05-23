const path    = require('path'),
      Extract = require('mini-css-extract-plugin');

const SRC_DIR = path.resolve(__dirname, 'src'),
      OUT_DIR = path.resolve(__dirname, 'build');

module.exports = {
    entry: path.resolve(SRC_DIR, 'index.js'),
    output: {
        path: OUT_DIR,
        filename: 'bundle.js'
    },
    plugins: [
        new Extract({
            filename: 'style.css'
        })
    ],
    module: {
        rules: [
            {
                test:  /\.(js|jsx)$/,
                loader: [
                    'babel-loader'
                ]
            },
            {
                test: /\.(css|scss)$/,
                use: [
                    Extract.loader,
                    { loader: 'css-loader' },
                    { loader: 'sass-loader' }
                ]
            }
        ]
    }
};
