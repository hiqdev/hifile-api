#!/usr/bin/env php
<?php

$args = [
    'url' => $argv[1],
    'dst' => $argv[2],
];

$dir = dirname($args['dst']);
if (!file_exists($dir)) {
    exec("mkdir -p $dir");
}
$lock = fopen("$dir/.lock", "c");

if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
    die('already working');
}

foreach ($args as &$arg) {
    $arg = escapeshellarg($arg);
}

$command = "/usr/bin/curl -o $args[dst] $args[url]";
exec($command);
