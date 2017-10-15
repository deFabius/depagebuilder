var gulp = require('gulp');
var sass = require('gulp-sass');

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
    wpSandbox: '/Users/MCUser/Sites/wp-sandbox/wp-content/plugins/depagebuilder'
};

gulp.task('default', function () {
    console.log(paths.wpSandbox);
});

gulp.task('php', function () {
    return gulp.src(paths.srcPHP).pipe(gulp.dest(paths.tmp));
});

gulp.task('sandbox', function () {
    return gulp.src(paths.src).pipe(gulp.dest(paths.wpSandbox));
});

gulp.task('sass', function(){
    return gulp.src(paths.srcSCSS)
      .pipe(sass()) // Using gulp-sass
      .pipe(gulp.dest(paths.wpSandbox + '/css'))
  });