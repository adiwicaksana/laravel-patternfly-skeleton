let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .js('resources/assets/js/patternfly.js', 'public/patternfly')
   .copy('node_modules/patternfly/dist/css/patternfly.min.css','public/patternfly')
   .copy('node_modules/patternfly/dist/css/patternfly-additions.min.css','public/patternfly')
   .copy('node_modules/datatables.net-jqui/css/dataTables.jqueryui.min.css','public/css')
   .copy('node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css','public/css')
   .copy('node_modules/select2/dist/css/select2.min.css','public/patternfly')
   .copy('node_modules/sweetalert2/dist/sweetalert2.min.css','public/patternfly')
   .copy('node_modules/patternfly/dist/fonts/*','public/fonts/')
   .copy('node_modules/patternfly/dist/img/*','public/patternfly/img/');
