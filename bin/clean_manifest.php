#!/usr/bin/env php
<?php declare(strict_types = 1);

function error(string $message): void {
    echo "Error: $message\n";
    exit(1);
}

function getFileArg(array $args): string {
    if (count($args) !== 2) {
        error("You must provide a filename");
    }
    
    $file = $args[1];
    if (!file_exists($file)) {
        error("No such file $file");
    }

    return $file;
}

function load(string $file): object {
    $json = file_get_contents($file);
    if ($json === false) {
        error("Failed to get contents of file $file");
    }
    
    $data = json_decode($json);
    if ($data === null) {
        error("Failed to decode contents of file $file");
    }

    return $data;
}

function sortFiles(object $manifest): void {
    usort($manifest->files, fn($a, $b) => $a->projectID < $b->projectID ? -1 : 1);
}

function save(object $manifest, string $file): void {
    $json = json_encode($manifest, JSON_PRETTY_PRINT);
    file_put_contents($file, $json);
}

$file = getFileArg($argv);
$manifest = load($file);
sortFiles($manifest);
save($manifest, $file);

echo "Manifest cleaned succesfully\n";

