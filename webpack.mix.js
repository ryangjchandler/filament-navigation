const mix = require('laravel-mix')

mix
    .postCss('resources/css/plugin.css', 'resources/dist/plugin.css', [
        require('tailwindcss')
    ])
    .js('resources/js/plugin.js', 'resources/dist/plugin.js');
