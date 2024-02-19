var gulp = require('gulp');
var del = require('del');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require("gulp-rename");
var less = require('gulp-less');
var uglifycss = require('gulp-uglifycss');
var livereload = require('gulp-livereload');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');
var zip = require('gulp-zip');
var ts = require("gulp-typescript");
var stripDebug = require('gulp-strip-debug');
var vendorJs = [
  "./node_modules/select2/dist/js/select2.min.js"
];
var vendorCss = [
  "./node_modules/font-awesome/css/font-awesome.min.css",
  "./node_modules/select2/dist/css/select2.min.css",
];
var fs = require('fs');
var path = require('path');
gulp.task('js-frontend', function jsFrontend() {
  return gulp.src(['assets/js/frontend/*.ts'])
    .pipe(ts({outFile: 'msf-frontend.js'}))
    .pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/scripts'))
    .pipe(livereload());
});
gulp.task('js-vendor', function jsVendor() {
  return gulp.src(vendorJs)
    .pipe(concat('msf-vendor.js'))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/scripts'));
});
gulp.task('js-backend', function jsBackend() {
  return gulp.src(['assets/js/backend/*.ts'])
    .pipe(ts({outFile: 'msf-backend.js'}))
    .pipe(uglify({mangle:false}))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest('dist/scripts'))
    .pipe(livereload());
});
gulp.task('js', gulp.parallel('js-frontend', 'js-vendor', 'js-backend'));
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
gulp.task('css-vendor', function cssVendor() {
  return gulp.src(['assets/vendor/css/*.css'].concat(vendorCss))
    .pipe(concat('msf-vendor.css'))
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
  return gulp.src(['node_modules/font-awesome/css/font-awesome.css', 'assets/css/backend/*.css', 'assets/css/backend/*.less'])
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
    return gulp.src('node_modules/font-awesome/fonts/*')
        .pipe(gulp.dest('dist/fonts'))
});
gulp.task('css', gulp.parallel('css-frontend', 'css-vendor', 'css-backend', 'fonts'));
gulp.task('pot', function pot() {
    return gulp.src('includes/**/*.php')
        .pipe(sort())
        .pipe(wpPot({
            domain: 'multi-step-form',
            destfile: 'mondula-form-wizard.pot',
            package: 'Multi Step Form',
            bugReport: 'https://mondula.com/en/contact/',
            lastTranslator: 'Mondula <info@mondula.com>',
            team: 'Mondula GmbH <wp-plugins@mondula.com>'
        }))
        .pipe(gulp.dest('lang'));
});
// Task to create an index.php file in the ./dist directory
gulp.task('secure-dist', function(done) {
  var content = '<?php\n// Silence is golden.\n';
  var distPath = path.join(__dirname, 'dist');
  var subfolders = ['styles', 'fonts', 'scripts'];
  // Ensure the dist directory and subfolders exist
  if (!fs.existsSync(distPath)){
      fs.mkdirSync(distPath);
  }
  subfolders.forEach(function(subfolder) {
      var subfolderPath = path.join(distPath, subfolder);
      if (!fs.existsSync(subfolderPath)) {
          fs.mkdirSync(subfolderPath);
      }
      // Write the content to index.php in each subfolder
      var filePath = path.join(subfolderPath, 'index.php');
      fs.writeFile(filePath, content, function(err) {
          if (err) {
              console.error(`Error creating index.php in ${subfolderPath}:`, err);
              done(err); // Signal failure
              return;
          }
          console.log(`Secure index.php file has been created in the ${subfolderPath}.`);
      });
  });
  // Also ensure root dist directory has index.php
  var rootIndexFilePath = path.join(distPath, 'index.php');
  fs.writeFile(rootIndexFilePath, content, function(err) {
      if (err) {
          console.error('Error creating index.php in dist directory:', err);
          done(err); // Signal failure
      } else {
          console.log('Secure index.php file has been created in the dist directory.');
          done(); // Signal completion
      }
  });
});
function watch() {
  gulp.watch('assets/js/frontend/*.ts', gulp.series('js-frontend'));
  gulp.watch('assets/js/backend/*.ts', gulp.series('js-backend'));
  gulp.watch('assets/css/*.css', gulp.series('css'));
  gulp.watch('assets/css/frontend/*.less', gulp.series('css-frontend'));
  gulp.watch('assets/css/backend/*.less', gulp.series('css-backend'));
}
gulp.task('default', gulp.series(gulp.parallel('js', 'css', 'fonts', 'pot', 'secure-dist' ), watch));
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
gulp.task('css-vendor:production', gulp.series(function cssVendorProd() {
  return gulp.src(['assets/vendor/css/*.css'].concat(vendorCss))
    .pipe(concat('msf-vendor.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist/styles'));
}));
gulp.task('css-backend:production', gulp.series(function cssBackendProd() {
  return gulp.src(['node_modules/font-awesome/css/font-awesome.css', 'assets/css/backend/*.css', 'assets/css/backend/*.less'])
    .pipe(less())
    .pipe(concat('msf-backend.min.css'))
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(gulp.dest('dist/styles'));
}));
gulp.task('js-frontend:production', gulp.series(function jsFrontendProd() {
  return gulp.src(['assets/js/frontend/*.ts'])
    .pipe(ts({outFile: 'msf-frontend.min.js'}))
    .pipe(stripDebug())
    .pipe(uglify())
    .pipe(gulp.dest('dist/scripts'));
}));
gulp.task('js-vendor:production', gulp.series(function jsVendorProd() {
  return gulp.src(vendorJs)
    .pipe(concat('msf-vendor.min.js'))
    .pipe(stripDebug())
    .pipe(uglify())
    .pipe(gulp.dest('dist/scripts'));
}));
gulp.task('js-backend:production', gulp.series(function jsBackendProd() {
  return gulp.src(['assets/js/backend/*.ts'])
    .pipe(ts({outFile: 'msf-backend.min.js'}))
    .pipe(stripDebug())
    .pipe(uglify())
    .pipe(gulp.dest('dist/scripts'));
}));
gulp.task('styles:production', gulp.parallel('css-frontend:production', 'css-vendor:production', 'css-backend:production'));
gulp.task('scripts:production', gulp.parallel('js-frontend:production', 'js-vendor:production', 'js-backend:production'));
gulp.task('build:production', gulp.series('clean:production', gulp.parallel('scripts:production', 'styles:production', 'fonts', 'pot','secure-dist') ));
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
gulp.task('copy:lang', gulp.series('copy:zip', function copyLang() {
  return gulp.src(
    'pkg/multi-step-form/lang/multi-step-form.pot')
    .pipe(rename('mondula-form-wizard.pot'))
    .pipe(gulp.dest('pkg/multi-step-form/lang/'));
}));
gulp.task('zip', gulp.series('copy:lang', function zipPackage() {
  return gulp.src('pkg/multi-step-form/**')
    .pipe(zip('multi-step-form.zip'))
    .pipe(gulp.dest('pkg'));
}));
