/// <reference path="./typings/tsd" />

import gulp = require("gulp");
import typescript = require("gulp-typescript");
import concat = require('gulp-concat');

gulp.task("typescript",()=>
    gulp.src("./apps/frontend/typescripts/**/*.ts")
        .pipe(typescript())
        .pipe(gulp.dest("./dist"))
);



gulp.task('init',["typescript"], function() {
    return gulp.src('./dist/**/*.js')
        .pipe(concat('public/js/frontend-scripts.js'))
        .pipe(gulp.dest('./'));
});

gulp.task('watch',function () {
    gulp.watch('./apps/frontend/typescripts/**/*.ts',['init']);
});