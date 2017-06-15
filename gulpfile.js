// Require all dev dependencies.
var gulp      = require('gulp'),
    zip       = require('gulp-zip');

var BASE_NAME = __dirname.match(/([^\/]*)\/*$/)[1];

// Zip src and options.
var ZIP_SRC_ARR = [
  './**',
  '!./composer.*',
  '!./gulpfile.js',
  '!./package.json',
  '!./README.md',
  '!./phpcs.xml',
  '!./phpunit.xml.dist',
  '!./{node_modules,node_modules/**}',
  '!./{bin,bin/**}',
  '!./{dist,dist/**}',
  '!./{vendor,vendor/**}',
  '!./{tests,tests/**}'
];
var ZIP_OPTS = { base: '..' };

// PHP Source.
var PHP_SRC = '**/*.php';

/*******************************************************************************
 *                                Gulp Tasks
 ******************************************************************************/

/**
 * Default gulp task. Initializes browserSync proxy server and watches src files
 * for any changes.
 *
 * CMD: gulp
 */
gulp.task('default', function() {

});


/**
 * Creates a zip file of the current project without any of the config and dev
 * files and saves it under the 'dist' folder.
 *
 * CMD: gulp zip
 */
gulp.task('zip', function(){
  return gulp.src( ZIP_SRC_ARR, ZIP_OPTS )
    .pipe( zip( BASE_NAME + '.zip' ) )
    .pipe( gulp.dest('dist') );
});
