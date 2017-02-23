// Include gulp
const gulp = require('gulp');

// Include Our Plugins
const jshint = require('gulp-jshint');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const cleanCSS = require('gulp-clean-css');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('assets/scripts/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

gulp.task('scripts:vendor', function() {
    return gulp.src([
            'assets/scripts/vendor/jquery.js',
            'assets/scripts/vendor/*.js'
        ])
        .pipe(sourcemaps.init())
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(rename('vendor.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('dist/js'))
});

gulp.task('scripts:app', function() {
    return gulp.src('assets/scripts/*.js')
        .pipe(sourcemaps.init())
        .pipe(concat('app.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(rename('app.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('dist/js'));
});

// Compile Our Sass
gulp.task('sass', function() {
    return gulp.src('assets/scss/*.scss')
        .pipe(sourcemaps.init())
        .pipe(concat('style.css'))
        .pipe(sass())
        .pipe(cleanCSS({compatibility: 'edge'}))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('dist/css'));
});

/*
gulp.task('stylesheets', function() {
    return gulp.src('assets/css/*.css')
        .pipe(sourcemaps.init())
        .pipe(concat('style.css'))
        .pipe(cleanCSS({compatibility: 'edge'}))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('dist/css'));
}); */

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('assets/scripts/*.js', ['lint', 'scripts:vendor', 'scripts:app']);
    gulp.watch('assets/scss/*.scss', ['sass']);
});

// Default Task
gulp.task('default', ['lint', 'scripts:vendor', 'scripts:app', 'sass', 'watch']);