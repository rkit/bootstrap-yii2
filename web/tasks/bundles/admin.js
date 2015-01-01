var gulp   = require('gulp'),
    bundle = require('../bundle.js'),
    // params
    destFolder = './web/compiled';

gulp.task('admin-css', function() {
    bundle('css', 'admin.css', destFolder, [
        './web/plugins/nprogress/nprogress.css',
        './web/css/admin/style.css',
    ]);
});

gulp.task('admin-js', function() {
    bundle('js', 'admin.js', destFolder, [
        './web/plugins/underscore/underscore-min.js',
        './web/plugins/jquery/jquery-2.1.1.min.js',
        './web/plugins/nprogress/nprogress.js',
        './web/plugins/jquery-form/jquery.form.min.js',
        './web/js/admin/forms.js',
        './web/js/admin/global.js',
    ]);
});