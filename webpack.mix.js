const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/manage-tickets.js', 'public/js')
    .js('resources/js/application.js', 'public/js')
    .js('resources/js/categories.js', 'public/js')
    .js('resources/js/factories.js', 'public/js')
    .js('resources/js/manage-sender.js', 'public/js')
    .js('resources/js/auth.js', 'public/js')
    .js('resources/js/conversation.js', 'public/js')
    .js('resources/js/dashboard.js', 'public/js')
    .js('resources/js/profile.js', 'public/js')
    .js('resources/js/account.js', 'public/js')
    .js('resources/js/settings.js', 'public/js')
    .js('resources/js/ticket-report.js', 'public/js')
    .js('resources/js/task-report.js', 'public/js')
    .js('resources/js/projects.js', 'public/js')
    .js('resources/js/canned-solutions.js', 'public/js')
    .js('resources/js/tasks.js', 'public/js')
    .sass('resources/sass/ticket.scss', 'public/css');
