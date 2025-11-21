const gulp = require('gulp');
const browserSync = require('browser-sync').create();

// Configuration
const config = {
    proxy: 'localhost:8888', // URL de votre site MAMP sans /neos
    port: 3000, // Port différent de MAMP
    files: [
        '**/*.php',
        'css/**/*.css',
        'js/**/*.js',
        'blocks/**/*.php',
        'blocks/**/*.css',
        'blocks/**/*.js'
    ]
};


// Tâche BrowserSync
function browserSyncTask() {
    browserSync.init({
        proxy: config.proxy,
        port: config.port,
        open: true,
        notify: false,
        files: config.files
    });
}

// Tâche de surveillance
function watchTask() {
    gulp.watch('**/*.php').on('change', browserSync.reload);
    gulp.watch('css/**/*.css').on('change', browserSync.stream());
    gulp.watch('js/**/*.js').on('change', browserSync.reload);
}

// Tâche par défaut
exports.default = gulp.series(browserSyncTask, watchTask);
exports.serve = browserSyncTask;