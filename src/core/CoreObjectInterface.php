<?php
    declare(strict_types=1);

    namespace pct\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\components\ComponentInterface;
    use pct\extensions\ExtensionInterface;

    use pct\core\CoreObjectArrayElementInterface;
    
    interface CoreObjectInterface extends CoreObjectArrayElementInterface {
        public function SetParent(?CoreObjectInterface $parent) : ?bool;
        public function GetParent() : ?CoreObjectInterface;

        public function ComponentIndex($component) : ?int;
        public function ComponentExists($component) : ?bool;
        public function ComponentNames(string $isA) : array;
        public function AddComponent(ComponentInterface $component, string $name = "", $position = null) : ?CoreObjectInterface;
        public function RemoveComponent($component) : ?CoreObjectInterface;
        public function SetComponent($offset, ComponentInterface $component) : ?CoreObjectInterface;
        public function GetComponent($offset) : ?CoreObjectInterface;
        public function GetComponents(string $isA) : array;

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