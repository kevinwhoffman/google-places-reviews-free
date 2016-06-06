/**
 * Google Places Reviews Gulp
 */

//Necessary packages
var gulp = require('gulp');
var wpPot = require('gulp-wp-pot');

//POT file task
gulp.task('pot', function () {
    return gulp.src('**/*.php')
        .pipe(wpPot({
            package: 'Google Places Reviews',
            domain: 'google-places-reviews',
            destFile: 'google-places-reviews.pot',
            lastTranslator: '',
            team: 'WordImpress <info@wordimpress.com>'
        }))
        .pipe(gulp.dest('languages'));
});

//Default task
gulp.task('default', function () {
    gulp.start('pot');
});