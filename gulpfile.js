var gulp = require('gulp');
var del = require('del');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require("gulp-rename");
var less = require('gulp-less');
var uglifycss = require('gulp-uglifycss');
var livereload = require('gulp-livereload');
var mainBowerFiles = require('main-bower-files');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');
var zip = require('gulp-zip');
var stripDebug = require('gulp-strip-debug');

gulp.task('js-frontend', function jsFrontend() {
  return gulp.src(['assets/js/frontend/*.js'])
    .pipe(concat('msf-frontend.js'))
    .pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/scripts'))
    .pipe(livereload());
});

gulp.task('js-frontend-vendor', function jsFrontendVendor() {
  return gulp.src(mainBowerFiles('**/*.js'))
    .pipe(concat('msf-vendor-frontend.js'))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/scripts'));
});

gulp.task('js-backend', function jsBackend() {
  return gulp.src(['assets/js/backend/*.js'])
    .pipe(concat('msf-backend.js'))
    .pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/scripts'))
    .pipe(livereload());
});

gulp.task('js', gulp.parallel('js-frontend', 'js-frontend-vendor', 'js-backend'));

gulp.task('css-frontend', function cssFrontend() {
  return gulp.src('assets/css/frontend/frontend.less')
    .pipe(less())
    .pipe(concat('msf-frontend.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/styles'))
    .pipe(livereload());
});

gulp.task('css-frontend-vendor', function cssFrontendVendor() {
  var bowerFiles = mainBowerFiles('**/*.css');
  return gulp.src(['assets/vendor/css/*.css'].concat(bowerFiles))
    .pipe(concat('msf-vendor-frontend.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/styles'))
    .pipe(livereload());
});

gulp.task('css-backend', function cssBackend() {
  return gulp.src(['bower_components/font-awesome/css/font-awesome.css', 'assets/css/backend/*.css', 'assets/css/backend/*.less'])
    .pipe(less())
    .pipe(concat('msf-backend.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/styles'))
    .pipe(livereload());
});

gulp.task('fonts', function fonts() {
    return gulp.src('bower_components/font-awesome/fonts/*')
        .pipe(gulp.dest('dist/fonts'))
});

gulp.task('css', gulp.parallel('css-frontend', 'css-frontend-vendor', 'css-backend', 'fonts'));

gulp.task('pot', function pot() {
    return gulp.src('includes/**/*.php')
        .pipe(sort())
        .pipe(wpPot({
            domain: 'multi-step-form',
            destfile: 'mondula-form-wizard.pot',
            package: 'Multi Step Form',
            bugReport: 'http://mondula.com/kontakt', // TODO
            lastTranslator: 'Lewe Ohlsen <lewe.ohlsen@mondula.com>',
            team: 'Mondula GmbH <wp-plugins@mondula.com>'
        }))
        .pipe(gulp.dest('lang'));
});

function watch() {
  gulp.watch('assets/js/frontend/*.js', gulp.series('js-frontend'));
  gulp.watch('assets/js/backend/*.js', gulp.series('js-backend'));
  gulp.watch('assets/css/*.css', gulp.series('css'));
  gulp.watch('assets/css/frontend/*.less', gulp.series('css-frontend'));
  gulp.watch('assets/css/backend/*.less', gulp.series('css-backend'));
}

gulp.task('default', gulp.series(gulp.parallel('js', 'css', 'fonts', 'pot'), watch));

gulp.task('clean:production', gulp.series(function cleanProd() {
  return del('dist/**/*');
}));

gulp.task('css-frontend:production', gulp.series(function cssFrontendProd() {
  return gulp.src('assets/css/frontend/frontend.less')
    .pipe(less())
    .pipe(concat('msf-frontend.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist/styles'));
}));

gulp.task('css-frontend-vendor:production', gulp.series(function cssFrontendVendorProd() {
  var bowerFiles = mainBowerFiles('**/*.css');
  return gulp.src(['assets/vendor/css/*.css'].concat(bowerFiles)) // , mainBowerFiles('**/*.css')])
    .pipe(concat('msf-vendor-frontend.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist/styles'));
}));

gulp.task('css-backend:production', gulp.series(function cssBackendProd() {
  return gulp.src(['bower_components/font-awesome/css/font-awesome.css', 'assets/css/backend/*.css', 'assets/css/backend/*.less'])
    .pipe(less())
    .pipe(concat('msf-backend.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist/styles'));
}));

gulp.task('js-frontend:production', gulp.series( function jsFrontendProd() {
  return gulp.src(['assets/js/frontend/*.js'])
    .pipe(concat('msf-frontend.min.js'))
    .pipe(stripDebug())
    .pipe(uglify())
    .pipe(gulp.dest('dist/scripts'));
}));

gulp.task('js-frontend-vendor:production', gulp.series(function jsFrontendVendorProd() {
  return gulp.src(mainBowerFiles('**/*.js'))
    .pipe(concat('msf-vendor-frontend.min.js'))
    .pipe(stripDebug())
    .pipe(uglify())
    .pipe(gulp.dest('dist/scripts'));
}));

gulp.task('js-backend:production', gulp.series(function jsBackendProd() {
  return gulp.src(['assets/js/backend/*.js'])
    .pipe(concat('msf-backend.min.js'))
    .pipe(stripDebug())
    .pipe(uglify())
    .pipe(gulp.dest('dist/scripts'));
}));

gulp.task('styles:production', gulp.parallel('css-frontend:production', 'css-frontend-vendor:production', 'css-backend:production'));

gulp.task('scripts:production', gulp.parallel('js-frontend:production', 'js-frontend-vendor:production', 'js-backend:production'));

gulp.task('build:production', gulp.series('clean:production', gulp.parallel('scripts:production', 'styles:production', 'fonts', 'pot')));

gulp.task('clean:zip', function cleanZip() {
  return del(['pkg/**/*']);
});

gulp.task('copy:zip', gulp.series('clean:zip', 'build:production', function copyZip() {
  return gulp.src(
      [
        'dist/**',
        'includes/**',
        'lang/*',
        'LICENSE',
        'index.php',
        'mondula-form-wizard.php',
        'readme.txt',
        'screenshot-1.jpg',
        'screenshot-2.jpg',
        'uninstall.php'
      ], {base: '.'})
    .pipe(gulp.dest('pkg/multi-step-form'));
}));

gulp.task('zip', gulp.series('copy:zip', function zipPackage() {
  return gulp.src('pkg/**/multi-step-form/**')
    .pipe(zip('multi-step-form.zip'))
    .pipe(gulp.dest('pkg'));
}));
