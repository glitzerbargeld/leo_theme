const gulp = require('gulp');
const babel = require('gulp-babel');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const browserSync = require('browser-sync');
const reload = browserSync.reload;
const concat = require('gulp-concat');
sass.compiler = require('node-sass');
projectURL: "http://sanaleo.local";

gulp.task('babel', () =>
  gulp
    .src('js/*.js')
    .pipe(sourcemaps.init())
    .pipe(
      babel({
        presets: ['@babel/env'],
      }),
    )
    .pipe(concat('all.js'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('.'))
    .pipe(reload({ stream: true })),
);

gulp.task('browser-sync', function () {
  const files = ['./scss/*.scss', './*.php', './js/*.js'];
  browserSync.init(files, {
    proxy: 'http://sanaleo.local',
    notify: true,
  });
  gulp.watch('./scss/**/*.scss', gulp.series(css));
  gulp.watch('./js/*.js'), gulp.series('babel');
  gulp.watch('./*.php').on('change', browserSync.reload);
});

const css = function () {
  return gulp
    .src('./scss/main.scss')
    .pipe(
      sass({
        outputStyle: 'compact',
      }).on('error', sass.logError),
    )
    .pipe(autoprefixer())
    .pipe(concat('style.css'))
    .pipe(gulp.dest('./'))
    .pipe(reload({ stream: true }));
};

const watch = function (cb) {
  gulp.watch('./scss/**/*.scss', gulp.series(css));
  gulp.watch('./js/*.js'), gulp.series('babel');
  gulp.watch('./*.php').on('change', browserSync.reload);
  cb();
};

exports.css = css;
exports.watch = watch;
exports.default = gulp.series(css, 'babel', watch, 'browser-sync');