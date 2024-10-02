<?php
/*function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }
    return $results;
}*/

function getDirContents($dir, $filter = '', &$results = array()) {
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        if(!is_dir($path)) {
            if(empty($filter) || preg_match($filter, $path)) $results[] = $path;
        } elseif($value != "." && $value != "..") {
            getDirContents($path, $filter, $results);
        }
    }

    return $results;
}

var_dump(getDirContents(__DIR__));

function show_files($start) {
    $contents = scandir($start);
    array_splice($contents, 0,2);
    echo "<ul>";
    foreach ( $contents as $item ) {
        if ( is_dir("$start/$item") && (substr($item, 0,1) != '.') ) {
            echo "<li>$item</li>";
            show_files("$start/$item");
        } else {
            echo "<li>$item</li>";
        }
    }
    echo "</ul>";
}

show_files('./');