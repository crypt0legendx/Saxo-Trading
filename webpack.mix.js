const mix = require('laravel-mix');
var path = require('path');

mix.js('resources/js/app.js', 'public/js').vue()
.postCss('resources/css/app.css', 'public/css/app.css')

mix.alias({
    '~': path.join(__dirname, 'resources/js/scss')
});
