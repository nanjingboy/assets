<?php
use Assets\Helper;

function javascript_include_tag($file, $echo = false)
{
    $includes = array();
    $files = Helper::includeAsset($file, 'js');
    foreach ($files as $file) {
        array_push($includes, "<script src='{$file}'></script>");
    }

    if ($echo === false) {
        return $includes;
    }

    foreach ($includes as $include) {
        echo "{$include}\n";
    }
}

function stylesheet_include_tag($file, $echo = false)
{
    $includes = array();
    $files = Helper::includeAsset($file, 'css');
    foreach ($files as $file) {
        array_push($includes, "<link href='{$file}' media='screen' rel='stylesheet'/>");
    }

    if ($echo === false) {
        return $includes;
    }

    foreach ($includes as $include) {
        echo "{$include}\n";
    }
}