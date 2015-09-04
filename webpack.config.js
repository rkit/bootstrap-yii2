var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var BowerWebpackPlugin = require('bower-webpack-plugin');
var ManifestPlugin = require('webpack-manifest-plugin');

module.exports = {
  entry: {
    admin: './web/js/admin',
    front: './web/js/front',
  },

  output: {
    path: path.join(__dirname, 'web/assets'),
    filename: '[name].[chunkhash].js',
  },

  module: {
    loaders: [
      {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract('style-loader', 'css-loader'),
      },
      {
        test: /\.(woff2|woff|svg|ttf|eot)([\?]?.*)$/,
        loader: 'file-loader?name=[name].[ext]',
      },
    ],

    noParse: [
      /\.min\.js/,
    ],
  },

  plugins: [
    new BowerWebpackPlugin({
      modulesDirectories: ['./vendor/bower'],
      manifestFiles: ['bower.json', '.bower.json'],
      includes: /.*/,
      excludes: /.*\.less$/,
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
    }),
    new ExtractTextPlugin('[name].[chunkhash].css'),
    new ManifestPlugin(),
    new webpack.optimize.DedupePlugin(),
    new webpack.optimize.OccurenceOrderPlugin(),
  ],
};
