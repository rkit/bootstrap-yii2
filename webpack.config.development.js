var webpack = require('webpack');
var config = require('./webpack.config');

config.devtool = 'eval';
config.plugins.push(
  new webpack.NoErrorsPlugin(),
  new webpack.DefinePlugin({
    'process.env': {
      'NODE_ENV': JSON.stringify('development'),
    },
  })
);

module.exports = config;
