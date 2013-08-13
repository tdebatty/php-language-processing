<?php

ini_set('include_path',
        ini_get('include_path').PATH_SEPARATOR.
        '/usr/share/php/PHPUnit'.PATH_SEPARATOR.
        dirname(__FILE__).'/../src/');

spl_autoload_register(function($class) {
    $parts = explode('\\', $class);
    
    $parts[] = str_replace('_', DIRECTORY_SEPARATOR, array_pop($parts));

    $path = implode(DIRECTORY_SEPARATOR, $parts);
    
    $file = stream_resolve_include_path($path.'.php');
    if($file !== false) {
        require $file;
    }
});

include_once dirname(__FILE__).'/../vendor/autoload.php';
?>