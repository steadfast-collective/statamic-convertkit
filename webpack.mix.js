let mix = require('laravel-mix');
var path = require('path');

mix.alias({
    '@': path.join(__dirname, 'resources/js')
})
.js('resources/js/addon.js', 'dist/js')
.postCss('resources/css/addon.css', 'dist/css', [
    require('tailwindcss')
])
.vue();
