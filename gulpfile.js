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
            package: 'Google Places Reviews Pro',
            domain: 'gpr',
            destFile: 'gpr.pot',
            lastTranslator: '',
            team: 'WordImpress <info@wordimpress.com>'
        }))
        .pipe(gulp.dest('lang'));
});

//Default task
gulp.task('default', function () {
    gulp.start('pot');
});