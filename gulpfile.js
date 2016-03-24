var gulp        = require('gulp'),
    php         = require('gulp-connect-php'),
    browserSync = require('browser-sync').create(),
    less        = require('gulp-less'),
    sourcemaps = require('gulp-sourcemaps');

var config = {
    source: './resources/assets/',
    less: 'less/**/*.less',
    lessEntry: 'less/style.less',
    dest: './public/assets/',
    styles: 'portal/newstyle',
    blades: './resources/views/**/*.blade.php'
};

// Static Server + watching less/html files
gulp.task('browser-sync', ['artisan', 'less'], function() {
    browserSync.init({
        proxy: 'http://127.0.0.1:8000'
    });
    gulp.watch(config.source + config.less, ['less']);
    gulp.watch(config.blades).on('change', browserSync.reload);
});

// Compile less into CSS & auto-inject into browsers
gulp.task('less', function() {
    return gulp.src(config.source + config.lessEntry)
        .pipe(sourcemaps.init())
        .pipe(less())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(config.dest + config.styles))
        .pipe(browserSync.stream());
});

gulp.task('artisan', function () {
    php.server({
        base: './public'
    });
});

gulp.task('watch', ['browser-sync'])