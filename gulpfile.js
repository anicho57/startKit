/**
 *  Web Starter Kit
 */

// Include Gulp & tools we'll use
var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var runSequence = require('run-sequence');
var browserSync = require('browser-sync');
var reload = browserSync.reload;
var paths = {
  siteRootPath: '/'
};
// bs host and sftp
var srv = {
  host: '127.0.0.1/startkit/',
  auth: 'key',
  remotePath: 'htdocs'
};

var AUTOPREFIXER_BROWSERS = [
  'ie >= 8',
  'ie_mob >= 8',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4',
  'bb >= 10'
];

// browser sync
gulp.task("bs", function() {
  browserSync.init({
      proxy: srv.host,
      // files: ["**/*.html","**/*.tpl","**/*.png","**/*.jpg"],
      open: false
  });
});

// browser sync reload
gulp.task("bsReload", function() {
    browserSync.reload();
});


gulp.task('sass', function () {
  return gulp.src(['_sass/**/*.scss'])
    .pipe( $.plumber({
      errorHandler: $.notify.onError( "Error: <%= error.message %>" )
    }))
    .pipe($.sourcemaps.init())
    // .pipe($.changed('.tmp/css', {extension: '.css'}))
    .pipe($.sass({
      precision: 10,
      onError: console.error.bind(console, 'Sass error:')
    }))
    .pipe($.autoprefixer({browsers: AUTOPREFIXER_BROWSERS}))
    .pipe($.sourcemaps.write('.tmp/maps/'))
    // .pipe($.sourcemaps.write())
    .pipe(gulp.dest('.tmp/css'))
    // .pipe($.if('*.css', $.csso()))
    .pipe(gulp.dest('css'))
    .pipe(reload({stream: true}));
});

gulp.task('sftpcss', function (){
  return gulp.src('.**/*.css')
    // .pipe($.changed('htdocs'))
    // .pipe($.if('*.css', $.csso()))
    // .pipe(gulp.dest('htdocs'))
    .pipe($.sftp(srv));
});

gulp.task('uploadcss', function () {
  runSequence('sass', 'sftpcss', 'bsReload');
});

gulp.task('tpl', function () {
  return gulp.src()
    .pipe(
      setTimeout(function (){
        browserSync.reload();
      },200)
    );
});

gulp.task('default', ['bs'], function (){
  gulp.watch(['./**/*.tpl'], reload);
  // gulp.watch(['_sass/**/*.{scss,css}'], ['uploadcss']);
  gulp.watch(['_sass/**/*.{scss,css}'], ['sass']);
});

