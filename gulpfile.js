/// <reference path="./typings/tsd" />
"use strict";
var gulp = require("gulp");
var typescript = require("gulp-typescript");
var concat = require('gulp-concat');
gulp.task("typescript", function () {
    return gulp.src("./apps/frontend/typescripts/**/*.ts")
        .pipe(typescript())
        .pipe(gulp.dest("./dist"));
});
gulp.task('init', ["typescript"], function () {
    return gulp.src('./dist/**/*.js')
        .pipe(concat('public/js/frontend-scripts.js'))
        .pipe(gulp.dest('./'));
});
gulp.task('watch', function () {
    gulp.watch('./apps/frontend/typescripts/**/*.ts', ['init']);
});
//# sourceMappingURL=gulpfile.js.map