const path = require('path'),
    CleanWebpackPlugin = require('clean-webpack-plugin'),
    ExtractTextPlugin  = require('extract-text-webpack-plugin');

const SRC_DIR = path.resolve(__dirname, 'src'),
      OUT_DIR = path.resolve(__dirname, 'build');

const extractSass = new ExtractTextPlugin({
    filename: 'style.css'
});

module.exports = {
    entry: path.resolve(SRC_DIR, 'index.js'),
    output: {
        path: OUT_DIR,
        filename: 'bundle.js'
    },
    plugins: [
        new CleanWebpackPlugin(['build']),
        extractSass
    ],
    module: {
        loaders: [
            {
                test:  /\.(js|jsx)$/,
                loaders: ['babel-loader']
            },
            {
                test: /\.(css|scss)$/,
                use: extractSass.extract({
                    fallback: 'style-loader',
                    use: ['css-loader', 'sass-loader']
                })
            }
        ]
    }
};
