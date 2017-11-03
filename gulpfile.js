// Change this constant to whatever virtual host you're using to develop wordpress
var testUrl = 'http://www.sandbox01.wp/wp-admin';
// Change to the dev wordpress plugin folder
var wpPluginFolder = '/Users/MCUser/Sites/wp-sandbox/wp-content/plugins/depagebuilder';

var fs = require('fs');
var gulp = require('gulp');
var sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
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
    distCSS: 'dist/css',
    distJS: 'dist/js',
    distPHP: 'dist',
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
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(gulp.dest(paths.wpSandbox + '/css'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('default', ['browserSync', 'sass'], function () {
    gulp.watch(paths.srcSCSS, ['sass']);
    gulp.watch(paths.srcPHP, ['sandbox']).on('change', browserSync.reload);
    gulp.watch(paths.srcJS, ['sandbox']).on('change', browserSync.reload);
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
});

gulp.task('build', ['update-package-version'], function () {
    gulp.src(paths.srcSCSS)
        .pipe(sass()) // Using gulp-sass
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(gulp.dest(paths.distCSS));

    gulp.src([paths.srcPHP, paths.srcJS, "package.json"])
        .pipe(gulp.dest(paths.dist));
});

gulp.task('update-package-version', function () {

    fs.readFile('./package.json', 'utf8', function (err, data) {
        if (err) {
            return console.log(err);
        }

        fs.writeFile('./package.old.json', data, function (err) {
            if (err) {
                return console.log('Backup failed:', err);
            }

            var versionRegEx = /(\"version\":\s\")([0-9\.]*)/;

            var version = versionRegEx.exec(data)[2].split('.');

            switch (process.argv[process.argv.length - 1]) {
                case '-V':
                    version[0]++;
                    break;
                case '-M':
                    version[1]++;
                    break;
                default:
                    version[2]++;
            }

            fs.writeFile('./package.json', data.replace(versionRegEx, '$1' + version.join('.')), function (err) {
                if (err) {
                    return console.log(err);
                }

                console.log("Version updated!");
            });
        });
    });
});
