<?php
Assets\Config::init(__DIR__ . '/configs/test/assets.php');

Assets\Helper::emptyDirectory(__DIR__ . '/app/tmp/assets');
Assets\Helper::emptyDirectory(__DIR__ . '/app/uglified/assets');