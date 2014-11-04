<?php
Assets\Config::init(__DIR__ . '/configs/test/assets.php');

Assets\Helper::removePath(__DIR__ . '/app/tmp');
Assets\Helper::removePath(__DIR__ . '/app/uglified');