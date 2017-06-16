// Require all dev dependencies.
var gulp     = require('gulp'),
    zip      = require('gulp-zip'),
		git 	   = require('gulp-git'),
		prompt 	 = require('gulp-prompt'),
		shell    = require('shelljs'),
		inquirer = require('inquirer'),
		replace  = require('gulp-replace'),
		semver   = require('semver'), // Versioning standard - http://semver.org/
		asynclib =  require('async'),
		colors   = require('colors'),
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

var current_branch;
var current_version;
var new_version;

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
	get_current_branch();
	console.log(('Current version: ' + current_version).green );
	gulp.src( BASE_FILE )
    .pipe(prompt.prompt({
        type: 'list',
        name: 'bump',
        message: 'What kind of release would you like to make?',
        choices: ['patch', 'minor', 'major']
    }, function(res){
			asynclib.waterfall([
	      function(callback){
	        callback(null, res.bump);
	      },
	      git_bump,
				git_push,
				git_tag
				//git_push
	    ], function (err, result) {
	      if( null !== err ){
	        console.log('ERROR: %j', err);
	      }
	    });
    }));
})

/*******************************************************************************
 *                                Functions
 ******************************************************************************/
function get_current_version(){
 	head = shell.head( {'-n':30}, BASE_FILE );
 	found = head.match( TAG_REGEX )
  current_version = found[1] + '.' + found[2] + '.' + found[3];
}

function get_current_branch(){
	git.revParse({args:'--abbrev-ref HEAD'}, function (err, hash) {
		if (err){
			console.error( (err.message).red );
		}
		else{
			current_branch = hash;
		}
	});
}

function git_bump(bump,callback){
	new_version = semver.inc( current_version, bump )
	shell.sed( '-i', TAG_REGEX, '* Version: ' + new_version, BASE_FILE );
	console.log(('New version: ' + new_version).green );
	gulp.src( '.' )
		.pipe(git.add({args: '--all'}))
		.pipe(git.commit('Testing gulp script'));
	return callback(null, current_branch);
}

function git_tag(callback){
	git.tag(new_version, 'Release' + new_version, {quiet:false}, function (err) {
		if (err){
			console.error( (err.message).red );
			console.error( 'Reverting changes...'.yellow );
			shell.sed( '-i', TAG_REGEX, '* Version: ' + current_version, BASE_FILE );
			return callback(err)
		}
		else{
			return callback(null, '--tags')
		}
	});
}

function git_push( branch, callback ){
	git.push('origin', branch, function (err) {
		if (err){
			console.error( (err.message).red );
			return callback(err);
		}
		else{
			return callback(null);
		}
	});
}
