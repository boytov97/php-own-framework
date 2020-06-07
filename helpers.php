<?php

if (!function_exists('print_die')) {
  function print_die($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    echo "\n";

    $info = debug_backtrace();
    print_r("File: {$info[0]['file']} Line: {$info[0]['line']}");
    exit();
  }
}

if (!function_exists('print_arr')) {
  function print_arr($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
  }
}