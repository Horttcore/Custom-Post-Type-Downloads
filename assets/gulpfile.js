/**
 * Filedirectories
 **/
var paths = {
	'dirs'	: {
		'from' 	: './src',
		'to'	: './dist'
	},
	'scss'	: {
		'files' : [
			'./src/scss/downloads.scss',
			'./src/scss/admin.downloads.scss'
		],
		'includePaths' : []
	},
	'js'	: {
		'files' : [] // handled by webpack
	},
};

/**
 * Dependencies
 **/
var gulp 			= require('gulp'),
	//concat 			= require('gulp-concat'),
	sass 			= require('gulp-sass'),
	//jsmin			= require('gulp-jsmin'),
	cssmin			= require('gulp-cssmin'),
	notify 			= require('gulp-notify'),
	//rename			= require('gulp-rename'),
	//lec 			= require('gulp-line-ending-corrector'),
	autoprefixer 	= require('gulp-autoprefixer');


/**
 * gulp minified build task
 **/
gulp.task('build', function(){
	gulp.src(paths.scss.files)
		.pipe(sass({
			includePaths: paths.scss.includePaths
		}).on('error', function(error){
			return notify().write(error);
		}))
		.pipe(autoprefixer({
			browsers: ['last 4 versions'],
			cascade: false
		}))
		.pipe(cssmin())
		.pipe(gulp.dest(paths.dirs.to + '/css/'));
});

/**
* gulp basic styles task
**/
gulp.task('styles', function(){
	return gulp.src(paths.scss.files)
		.pipe(sass({
		includePaths: paths.scss.includePaths
		}).on('error', function(error){
			return notify().write(error);
		}))
		.pipe(autoprefixer({
			browsers: ['last 4 versions'],
			cascade: true
		}))
		.pipe(gulp.dest(paths.dirs.to + '/css/'));
});
/**
* gulp basic watch task
**/
gulp.task('default', ['styles'], function(){
	return gulp.watch(paths.dirs.from + '/**/*', ['styles']);
})