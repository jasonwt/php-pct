<?php
    declare(strict_types=1);

    namespace pct\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\components\ComponentInterface;
    use pct\extensions\ExtensionInterface;

    use pct\core\CoreObjectArrayElementInterface;


    use pct\traits\core\CoreObjectComponentsTraitsInterface;
    use pct\traits\core\CoreObjectExtensionsTraitsInterface;
    
    interface CoreObjectInterface extends CoreObjectArrayElementInterface, CoreObjectComponentsTraitsInterface, CoreObjectExtensionsTraitsInterface {
        public function SetParent(?CoreObjectInterface $parent) : ?bool;
        public function GetParent() : ?CoreObjectInterface;

        public function Disabled() : bool;
        public function Enable() : bool;
        public function IsEnabled() : bool;
        
        //public function CanCall(string $methodName): bool;


    }
?>