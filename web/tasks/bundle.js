var gulp   = require('gulp'),
    minCss = require('gulp-minify-css'),
    minJs  = require('gulp-uglify'),
    concat = require('gulp-concat'),
    // params
    params = require('./default.js');

module.exports = function(type, destFile, destFolder, files) {
    var build = gulp
        .src(files)
        .pipe(concat(destFile));
    
    if (type == 'css') {
        build.pipe(minCss({ keepBreaks: true })); 
    }  else {
        if (!params.debug) {
            build.pipe(minJs());
        } 
    }
    
    build.pipe(gulp.dest(destFolder));
};