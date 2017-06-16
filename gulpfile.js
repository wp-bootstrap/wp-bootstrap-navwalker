// Require all dev dependencies.
var gulp     = require('gulp'),
    zip      = require('gulp-zip'),
		git 	   = require('gulp-git'),
		prompt 	 = require('gulp-prompt'),
		shell    = require('shelljs'),
		inquirer = require('inquirer'),
		replace  = require('gulp-replace'),
		semver   = require('semver');
		colors   = require('colors');
		rmdir	   = require('rmdir');

const BASE_NAME = __dirname.match(/([^\/]*)\/*$/)[1];
const BASE_FILE = 'wp-bootstrap-navwalker.php';
const TAG_REGEX = /\*\s?version:\s?(?:(\d+)\.)?(?:(\d+)\.)?(\*|\d+)/i;

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

var current_version;
var new_version;
var to_replace;

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

gulp.task('clean', function(){
	rmdir('./dist');
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

gulp.task('tag', function(){
	get_current_version();
	console.log(('Current version: ' + current_version).green );
	gulp.src( BASE_FILE )
    .pipe(prompt.prompt({
        type: 'list',
        name: 'bump',
        message: 'What type of release would you like to do?',
        choices: ['patch', 'minor', 'major']
    }, function(res){
				new_version =  semver.inc( current_version, res.bump )
				console.log(('New version: ' + new_version).green );
        shell.sed( '-i', TAG_REGEX, '* Version: ' + new_version, BASE_FILE );
				git.tag(new_version, 'Release' + new_version, {quiet:false}, function (err) {
					if (err){
						console.error( (err.message).red );
						console.error( 'Reverting changes...'.yellow );
						shell.sed( '-i', TAG_REGEX, '* Version: ' + current_version, BASE_FILE );
						return;
					}
					else{
						git.push('origin', '--tags', function (err) {
							if (err){
								console.error( (err.message).red );
								return;
							}
						});
					}
				});
    }));
})

function get_current_version(){
	head = shell.head( {'-n':30}, BASE_FILE );
	found = head.match( TAG_REGEX )
	current_version = found[1] + '.' + found[2] + '.' + found[3];
}

gulp.task('release', ['bump','tag']);
