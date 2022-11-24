<?php
    declare(strict_types=1);
    
    namespace pct\debugging;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    interface DebuggingInterface {
        public static function DString(int $startingIndex, int $maxRecords, array $arguments) : string;
    }
?>