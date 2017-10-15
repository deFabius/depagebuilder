// Change this constant to whatever virtual host you're using to develop wordpress
var testUrl = 'http://www.sandbox01.wp';
// Change to the dev wordpress plugin folder
var wpPluginFolder = '/Users/MCUser/Sites/wp-sandbox/wp-content/plugins/depagebuilder';

var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();

var paths = {
    src: 'src/**/*',
    srcHTML: 'src/**/*.html',
    srcSCSS: 'src/scss/**/*.scss',
    srcJS: 'src/**/*.js',
    srcPHP: 'src/**/*.php',
    tmp: 'tmp',
    tmpIndex: 'tmp/index.html',
    tmpCSS: 'tmp/css/**/*.css',
    tmpJS: 'tmp/**/*.js',
    tmpPHP: 'tmp/**/*.php',
    dist: 'dist',
    distIndex: 'dist/index.html',
    distCSS: 'dist/**/*.css',
    distJS: 'dist/**/*.js',
    distPHP: 'dist/**/*.php',
    wpSandbox: wpPluginFolder
};

gulp.task('default', function () {
    console.log(paths.wpSandbox);
});

gulp.task('php', function () {
    return gulp.src(paths.srcPHP).pipe(gulp.dest(paths.tmp));
});

gulp.task('sandbox', function () {
    return gulp.src(paths.src)
        .pipe(gulp.dest(paths.wpSandbox))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('sass', function () {
    return gulp.src(paths.srcSCSS)
        .pipe(sass()) // Using gulp-sass
        .pipe(gulp.dest(paths.wpSandbox + '/css'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('default', ['browserSync', 'sass'], function () {
    gulp.watch(paths.srcSCSS, ['sass']);
    gulp.watch(paths.srcPHP, ['sandbox']).on('change', browserSync.reload);
});

gulp.task('browserSync', function () {
    let files = [
        paths.srcSCSS,
        paths.srcPHP
    ];
    browserSync.init(files,
        {
            proxy: testUrl,
            notify: false
        });
});

gulp.task('reload', function () {
    browserSync.reload()
})