var gulp = require('gulp');
var concat = require('gulp-concat');
var newer = require('gulp-newer');
var uglify = require('gulp-uglify');
var rename = require("gulp-rename");
var debug = require('gulp-debug-streams');
var uglifycss = require('gulp-uglifycss');

var base = 'assets/js';

gulp.task('js', function() {
  gulp.src(['assets/js/*.js', '!assets/js/*.min.js'])
    .pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('assets/js/'));
});

gulp.task('css', function () {
  gulp.src(['assets/css/*.css', '!assets/css/*.min.css'])
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('assets/css/'));
});

gulp.task('watch', function () {
    gulp.watch('assets/js/*.js', ['js']);
    gulp.watch('assets/css/*.css', ['css']);
});

gulp.task('default', ['js', 'css', 'watch']);
