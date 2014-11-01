<?php
Assets\Config::init(
    __DIR__ . '/app',
    __DIR__ . '/app/assets/javascripts',
    __DIR__ . '/app/assets/stylesheets',
    __DIR__ . '/app/assets/images',
    __DIR__ . '/app/assets/fonts'
);

if (is_dir(__DIR__ . '/app/tmp')) {
    rmrdir(__DIR__ . '/app/tmp');
}
