var gulp = require('gulp');
var concat = require('gulp-concat');
var newer = require('gulp-newer');
var uglify = require('gulp-uglify');
var rename = require("gulp-rename");
var debug = require('gulp-debug-streams');

var base = 'assets/js',
    raw = base + '/javascripts';


gulp.task('javascripts', function () {
  gulp.src('assets/js/javascripts/*.js')
    .pipe(concat('frontend.js'))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('assets/js/'));
});

gulp.task('uglify', function() {
  gulp.src(['assets/js/*.js', '!assets/js/*.min.js'])
    //.pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('assets/js/'));
})

gulp.task('watch', function () {
    gulp.watch('assets/js/javascripts/*.js', ['javascripts']);

    gulp.watch('**', ['sync']).on('change', function (evt) {
        if (evt.type === 'deleted') {
            grunt.log(evt);
        }
    });
});

gulp.task('sync', function () {
    var dest = '/var/www/html/wordpress/wp-content/plugins/mondula-form-wizard';
    return gulp.src('**')
        .pipe(newer(dest))
        .pipe(gulp.dest(dest))
});

gulp.task('default', ['javascripts', 'uglify', 'watch', 'sync']);
