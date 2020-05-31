<?php

if (!function_exists('print_die')) {
  function print_die($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    exit();
  }
}