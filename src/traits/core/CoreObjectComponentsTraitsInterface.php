<?php
    declare(strict_types=1);

    namespace pct\traits\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectInterface;
    use pct\components\ComponentInterface;
    
    interface CoreObjectComponentsTraitsInterface {
        public function ComponentIndex($component) : ?int;
        public function ComponentExists($component) : ?bool;
        public function ComponentNames(string $isA) : array;
        public function AddComponent(ComponentInterface $component, string $name = "", $position = null) : ?CoreObjectInterface;
        public function RemoveComponent($component) : ?CoreObjectInterface;
        public function SetComponent($offset, ComponentInterface $component) : ?CoreObjectInterface;
        public function GetComponent($offset) : ?CoreObjectInterface;
        public function GetComponents(string $isA) : array;
    }
?>