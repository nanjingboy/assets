<?php
Assets\Config::init(__DIR__ . '/configs/test/assets.php');

if (is_dir(__DIR__ . '/app/tmp')) {
    rmrdir(__DIR__ . '/app/tmp');
}

if (is_dir(__DIR__ . '/app/uglified')) {
    rmrdir(__DIR__ . '/app/uglified');
}