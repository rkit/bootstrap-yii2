var gulp    = require('gulp'),
    gutil   = require('gulp-util'),
    broSync = require('browser-sync'),
    params  = require('minimist')(process.argv.slice(2), { boolean: 'debug', boolean: 'sync' });

params.sync ? gutil.log('Sync:',  gutil.colors.cyan(params.sync)) : null;
params.debug ? gutil.log('Debug:',  gutil.colors.cyan(params.debug)) : null;

/* Default tasks
-------------------------------------------------- */

var defaultTasks = [
    'admin-css', 'admin-js',
    'front-css', 'front-js',
];

if (params.sync) {
    defaultTasks.push('browser-sync');
}

gulp.task('default', defaultTasks, function() {
    if (params.debug) {
        if (params.sync) {
            gulp.watch('./web/compiled/*.*', broSync.reload);
        }

        gulp.watch('./web/plugins/**/*.css', ['front-css', 'admin-css']);
        gulp.watch('./web/plugins/**/*.js', ['front-js', 'admin-js']);
        
        gulp.watch('./web/css/admin/**/*.css', ['admin-css']);
        gulp.watch('./web/js/admin/**/*.js', ['admin-js']);
        
        gulp.watch('./web/css/front/**/*.css', ['front-css']);
        gulp.watch('./web/js/front/**/*.js', ['front-js']);
    }
});