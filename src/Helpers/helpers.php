<?php

function niceTitle($str)
{
    $parts = explode(' ', $str);
    $parts[0] = preg_replace('~([a-z])([A-Z])~', '\\1 \\2', $parts[0]);
    return implode(' ', $parts);
}