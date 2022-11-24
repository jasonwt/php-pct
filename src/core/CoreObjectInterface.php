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
        public function AddComponent(ComponentInterface $component, string $name = "", $position = null) : ?CoreObjectInterface;
        public function RemoveComponent($component) : ?CoreObjectInterface;
    }




?>