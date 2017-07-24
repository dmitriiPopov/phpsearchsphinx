<?php
/**
 * Scripts for autoload classes from files tree
 */
spl_autoload_register(function ($className) {
    //if file with class is situated in internal docroot (first part of namespace `app\` means docroot directory)...
    if (strpos($className, 'app\\') === 0) {
        //explode namespace on parts (similar with path to file)
        $partsOfPath = explode('\\', str_replace('app\\', '', $className));
        //prepare path to file
        $filePathToClass = sprintf('%s/%s.php', __DIR__, implode('/', $partsOfPath));
        //include file with selected className
        include($filePathToClass);
    }
});