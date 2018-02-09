const path = require('path'),

    CleanWebpackPlugin = require('clean-webpack-plugin');

const SRC_DIR = path.resolve(__dirname, 'src'),
    OUT_DIR = path.resolve(__dirname, 'build');

module.exports = {
    entry: path.resolve(SRC_DIR, 'index.js'),
    output: {
        path: OUT_DIR,
        filename: 'bundle.js'
    },
    plugins: [
        new CleanWebpackPlugin(['build'])
    ],
    module: {
        loaders: [
            {
                test:  /\.(js|jsx)$/,
                loaders: ['babel-loader']
            }
        ]
    }
};
