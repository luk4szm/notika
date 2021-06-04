var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

gulp.task('fontawesone', function () {
    return gulp.src([
        'node_modules/@fortawesome/fontawesome-free/js/*.js',
    ])
        .pipe(concat('fontawesome.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/assets/js'));
});

gulp.task('chart.js', function () {
    return gulp.src([
        'node_modules/chart.js/dist/chart.js',
    ])
        .pipe(concat('chart.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/assets/js'));
});

gulp.task('footer', function () {
    return gulp.src([
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'src/_js/spinner.js',
        'src/_js/row-link.js'
    ])
        .pipe(concat('footer.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/assets/js'));
});

gulp.task('default', gulp.series('fontawesone', 'chart.js', 'footer'));