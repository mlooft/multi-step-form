var gulp = require('gulp');
var del = require('del');
var concat = require('gulp-concat');
var newer = require('gulp-newer');
var uglify = require('gulp-uglify');
var rename = require("gulp-rename");
var debug = require('gulp-debug-streams');
var less = require('gulp-less');
var uglifycss = require('gulp-uglifycss');
var livereload = require('gulp-livereload');
var mainBowerFiles = require('main-bower-files');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');
var zip = require('gulp-zip');

var base = 'assets/js';

gulp.task('js-frontend', function() {
  gulp.src(['assets/js/frontend/*.js'])
    .pipe(concat('frontend.js'))
    .pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist'))
    .pipe(livereload());
});

gulp.task('js-frontend-vendor', function() {;
  gulp.src(mainBowerFiles('**/*.js'))
    .pipe(concat('vendor-frontend.js'))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist'));
});

gulp.task('js-backend', function() {
  gulp.src(['assets/js/backend/*.js'])
    .pipe(concat('backend.js'))
    .pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist'))
    .pipe(livereload());
});

gulp.task('js', ['js-frontend', 'js-frontend-vendor', 'js-backend']);

gulp.task('css-frontend', function () {
  gulp.src(['assets/css/frontend/*.css', 'assets/css/frontend/*.less'])
    .pipe(less())
    .pipe(concat('frontend.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist'))
    .pipe(livereload());
});

gulp.task('css-frontend-vendor', function () {
  gulp.src(mainBowerFiles('**/*.css'))
    .pipe(concat('vendor-frontend.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist'))
    .pipe(livereload());
});

gulp.task('css-backend', function () {
  gulp.src(['assets/css/backend/*.css', 'assets/css/backend/*.less'])
    .pipe(less())
    .pipe(concat('backend.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist'))
    .pipe(livereload());
});

gulp.task('css', ['css-frontend', 'css-frontend-vendor', 'css-backend']);

gulp.task('pot', function () {
    return gulp.src('**/*.php')
        .pipe(sort())
        .pipe(wpPot({
            domain: 'mondula-multistep-forms', // TODO
            destfile: 'mondula-form-wizard.pot', // TODO
            package: 'mondula-multistep-forms', // TODO
            bugReport: 'http://mondula.com/kontakt', // TODO
            lastTranslator: 'Lewe Ohlsen <lewe.ohlsen@mondula.com>',
            team: 'Mondula GmbH <wp-plugins@mondula.com>'
        }))
        .pipe(gulp.dest('lang'));
});

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch('assets/js/frontend/*.js', ['js-frontend']);
    gulp.watch('assets/js/backend/*.js', ['js-backend']);
    gulp.watch('assets/css/*.css', ['css']);
    gulp.watch('assets/css/frontend/*.less', ['css-frontend']);
    gulp.watch('assets/css/backend/*.less', ['css-backend']);
});

gulp.task('default', ['js', 'css', 'watch']);

gulp.task('clean:production', function () {
  return del('dist/**/*');
});

gulp.task('css-frontend:production', ['clean:production'], function () {
  return gulp.src(['assets/css/frontend/*.css', 'assets/css/frontend/*.less'])
    .pipe(less())
    .pipe(concat('frontend.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist'));
});

gulp.task('css-frontend-vendor:production', ['clean:production'], function () {
  return gulp.src(mainBowerFiles('**/*.css'))
    .pipe(concat('vendor-frontend.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist'));
});

gulp.task('css-backend:production', ['clean:production'], function () {
  return gulp.src(['assets/css/backend/*.css', 'assets/css/backend/*.less'])
    .pipe(less())
    .pipe(concat('backend.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist'));
});

gulp.task('js-frontend:production', ['clean:production'], function () {
  return gulp.src(['assets/js/frontend/*.js'])
    .pipe(concat('frontend.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('dist'));
});

gulp.task('js-frontend-vendor:production', ['clean:production'], function () {
  return gulp.src(mainBowerFiles('**/*.js'))
    .pipe(concat('vendor-frontend.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('dist'));
});

gulp.task('js-backend:production', ['clean:production'], function () {
  return gulp.src(['assets/js/backend/*.js'])
    .pipe(concat('backend.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('dist'));
});

gulp.task('styles:production', ['css-frontend:production', 'css-frontend-vendor:production', 'css-backend:production']);

gulp.task('scripts:production', ['js-frontend:production', 'js-frontend-vendor:production', 'js-backend:production']);

gulp.task('build:production', ['scripts:production', 'styles:production']);

gulp.task('clean:zip', function () {
  return del('pkg/**/*');
})

gulp.task('copy:zip', ['clean:zip', 'build:production'], function () {
  return gulp.src(
      [
        'dist/*', 
        'includes/**',
        'lang/*', 
        'LICENSE', 
        'index.php',
        'mondula-form-wizard.php',
        'readme.md',
        'readme.txt',
        'uninstall.php' 
      ], {base: '.'})
    .pipe(gulp.dest('pkg/multi-step-form'));
}); 

gulp.task('zip', ['copy:zip'], function () {
  return gulp.src('pkg/**/multi-step-form/**')
    .pipe(zip('multi-step-form.zip'))
    .pipe(gulp.dest('pkg'));
});
