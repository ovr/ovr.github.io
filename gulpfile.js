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
        './public/css/component.css',
        './public/src/vendor/rrssb/css/rrssb.css'
    ])
    .pipe(concat('all.css'))
    .pipe(minifyCSS({removeEmpty: true, keepSpecialComments: 0}))
    .pipe(gulp.dest('./public/dist/'))
});

gulp.task('default', ['stylesheets']);