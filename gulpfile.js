var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var jshint = require('gulp-jshint');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin'),
    cache = require('gulp-cache');
var minifycss = require('gulp-minify-css');
var wpPot = require('gulp-wp-pot');
var sort = require('gulp-sort');

gulp.task('images', function(){
    gulp.src('assets/images/**/*')
        .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
        .pipe(gulp.dest('dist/images/'));
});

gulp.task('styles', function(){
    return gulp.src('assets/css/**/*.css')
        .pipe(plumber({
            errorHandler: function (error) {
                console.log(error.message);
                this.emit('end');
            }}))
        .pipe(autoprefixer('last 2 versions'))
        .pipe(gulp.dest('dist/css/'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss())
});

gulp.task('scripts', function(){
    return gulp.src('assets/js/**/*.js')
        .pipe(plumber({
            errorHandler: function (error) {
                console.log(error.message);
                this.emit('end');
            }}))
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(gulp.dest('dist/js/'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('dist/js/'))
});

gulp.task('pot', function() {
    return gulp.src('**/*.php')
        .pipe(sort())
        .pipe(wpPot( {
            package: 'Google Places Reviews Free',
            domain: 'gpr',
            destFile:'gpr.pot',
            lastTranslator: '',
            team: 'WordImpress <info@wordimpress.com>'
        } ))
        .pipe(gulp.dest('lang'));
});

gulp.task('default', function(){
    gulp.start('styles', 'scripts', 'images', 'pot');
});

gulp.task('watch', function (){
    gulp.watch('assets/css/**/*.css', ['styles']);
    gulp.watch('assets/js/**/*.js', ['scripts']);
});