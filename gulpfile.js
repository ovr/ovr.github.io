var gulp = require('gulp'),
    concat = require('gulp-concat'),
    gutil = require('gulp-util'),
    minifyCSS = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename');

gulp.task('stylesheets', function () {
    gulp.src([
        './public/src/vendor/bootstrap/dist/css/bootstrap.min.css',
        './public/src/vendor/fontawesome/css/font-awesome.min.css',
        './public/css/normalize.css',
        './public/css/demo.css',
        './public/css/component.css'
    ])
    .pipe(concat('all.css'))
    .pipe(minifyCSS({removeEmpty: true}))
    .pipe(gulp.dest('./public/dist/'))
});

/**
gulp.task('scripts', function () {
    gulp.src([
        './public/src/vendor/modernizr/modernizr.js'
    ])
    .pipe(concat('all.js'))
    .pipe(gulp.dest('dist'))
    .pipe(rename('all.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./dist/'))
});*/

gulp.task('default', ['stylesheets']);