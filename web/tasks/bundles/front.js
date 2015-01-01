var gulp   = require('gulp'),
    bundle = require('../bundle.js'),
    // params
    destFolder = './web/compiled';

gulp.task('front-css', function() {
    bundle('css', 'front.css', destFolder, [
        './web/css/front/style.css',
    ]);
});

gulp.task('front-js', function() {
    bundle('js', 'front.js', destFolder, [
        './web/plugins/jquery/jquery-2.1.1.min.js',
        './web/js/front/global.js',
    ]);
});