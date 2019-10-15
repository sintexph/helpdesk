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

    /*
    .babel('public/js/projects.js', 'public/js/projects.js')
    .babel('public/js/canned-solutions.js', 'public/js/canned-solutions.js')
    .babel('public/js/tasks.js', 'public/js/tasks.js')
    .babel('public/js/application.js', 'public/js/application.js')
    .babel('public/js/manage-tickets.js', 'public/js/manage-tickets.js')
    .babel('public/js/categories.js', 'public/js/categories.js')
    .babel('public/js/factories.js', 'public/js/factories.js')
    .babel('public/js/manage-sender.js', 'public/js/manage-sender.js')
    .babel('public/js/auth.js', 'public/js/auth.js')
    .babel('public/js/conversation.js', 'public/js/conversation.js')
    .babel('public/js/dashboard.js', 'public/js/dashboard.js')
    .babel('public/js/profile.js', 'public/js/profile.js')
    .babel('public/js/account.js', 'public/js/account.js')
    .babel('public/js/settings.js', 'public/js/settings.js')
    .babel('public/js/ticket-report.js', 'public/js/ticket-report.js')
    .babel('public/js/task-report.js', 'public/js/task-report.js')
    */
