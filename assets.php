<?php
use Assets\Helper;

function javascript_include_tag($file)
{
    $includes = array();
    $files = Helper::includeAsset($file, 'js');
    foreach ($files as $file) {
        array_push($includes, "<script src='{$file}'></script>");
    }

    return $includes;
}

function stylesheet_include_tag($file)
{
    $includes = array();
    $files = Helper::includeAsset($file, 'css');
    foreach ($files as $file) {
        array_push($includes, "<link href='{$file}' media='screen' rel='stylesheet'/>");
    }

    return $includes;
}