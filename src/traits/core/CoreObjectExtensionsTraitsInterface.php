<?php
    declare(strict_types=1);

    namespace pct\traits\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectInterface;
    use pct\extensions\ExtensionInterface;
    
    interface CoreObjectExtensionsTraitsInterface {        

        public function ExtensionIndex($extension) : ?int;
        public function ExtensionExists($extension) : ?bool;
        public function ExtensionNames(string $isA) : array;
        public function AddExtension(ExtensionInterface $extension, string $name = "", $position = null) : ?CoreObjectInterface;
        public function RemoveExtension($extension) : ?CoreObjectInterface;
        public function SetExtension($offset, ExtensionInterface $extension) : ?CoreObjectInterface;
        public function GetExtension($offset) : ?CoreObjectInterface;
        public function GetExtensions(string $isA) : array;
     
        
    }
?>