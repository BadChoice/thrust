let mix = require('laravel-mix');

mix
    .babel([
        'src/resources/js/jquery-2.1.0.min.js',
        'src/resources/js/jquery-ui-1.10.4.custom.min',
        'src/resources/js/utils.js',
        'src/resources/js/searcher.js',
        'src/resources/js/visibility.js',
        'src/resources/js/select2.min.js',
        'src/resources/js/scrollsaver.min.js',
        'src/resources/js/places.js',
        'src/resources/js/jquery.popupoverlay.js',
    ], 'src/resources/js/thrust.min.js');

