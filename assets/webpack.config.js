var path = require('path'),
    fs = require('fs'),
    webpack = require('webpack'),
    ExtractTextPlugin = require("extract-text-webpack-plugin"),
    BowerWebpackPlugin = require("bower-webpack-plugin"),
    assetsDir = path.join(__dirname , './../web/assets'),
    ENV_PRODUCTION = process.env.NODE_ENV === 'production';

module.exports = {
    context: path.join(__dirname, '.'),

    entry: {
        'admin': './bundle.admin',
        'front': './bundle.front',
    },

    output: {
        path: assetsDir + '/bundles',
        filename: "[name].js",
    },

    module: {
        loaders: [
            { test: /\.css$/, loader: ExtractTextPlugin.extract("style-loader", "css-loader") },
            { test: /\.(woff2|woff|svg|ttf|eot)([\?]?.*)$/, loader: "file-loader?name=[name].[ext]" },
        ],

        noParse: [
            /\.min\.js/
        ],
    },

    plugins: [
        new ExtractTextPlugin("[name].css"),

        new BowerWebpackPlugin({
            modulesDirectories: ['./../vendor/bower'],
            manifestFiles: ['bower.json', '.bower.json'],
            includes: /.*/,
            excludes: /.*\.less$/,
        }),

        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
        }),

        new webpack.optimize.DedupePlugin(),
        //new webpack.optimize.CommonsChunkPlugin("commons.js")

        function() {
            this.plugin('done', function(stats) {
                var hash = stats.toJson().hash;
                fs.exists(assetsDir + '/' + hash, function (exist) {
                    if (!exist) {
                        fs.symlink('./bundles', assetsDir  + '/' + hash, 'dir');
                        fs.writeFileSync(assetsDir + '/hash', hash);
                    }
                });
            });
        },
    ],
};
