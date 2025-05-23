<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function assets_url() {
    return base_url() . 'assets/';
}

function base62_encode($num) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = 62;
    $result = '';
    if ($num == 0) return '0';
    while ($num > 0) {
        $result = $chars[$num % $base] . $result;
        $num = intval($num / $base);
    }
    return $result;
}

function base62_decode($str) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = 62;
    $length = strlen($str);
    $num = 0;
    for ($i = 0; $i < $length; $i++) {
        $pos = strpos($chars, $str[$i]);
        if ($pos === false) {
            return false;
        }
        $num = $num * $base + $pos;
    }
    return $num;
}

