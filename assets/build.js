var webpack  = require('webpack'),
    config   = require('./webpack.config'),
    compiler = webpack(config);

compiler.run(function () {
    console.log('Run…');
});
