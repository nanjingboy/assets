<?php
use Assets\Helper;

function javascript_include_tag($file, $prefix = null)
{
    $includes = array();
    $files = Helper::includeAsset($file, 'js');
    $prefix = rtrim($prefix, DIRECTORY_SEPARATOR);
    foreach ($files as $file) {
        array_push($includes, "<script src='{$prefix}{$file}'></script>");
    }

    return $includes;
}

function stylesheet_include_tag($file, $prefix = null)
{
    $includes = array();
    $files = Helper::includeAsset($file, 'css');
    $prefix = rtrim($prefix, DIRECTORY_SEPARATOR);
    foreach ($files as $file) {
        array_push($includes, "<link href='{$prefix}{$file}' media='screen' rel='stylesheet'/>");
    }

    return $includes;
}