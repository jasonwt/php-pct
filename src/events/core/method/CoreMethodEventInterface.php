<?php
    declare(strict_types=1);

    namespace pct\events\core\method;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectInterface;
    use pct\events\core\CoreEventInterface;

    interface CoreMethodEventInterface extends CoreEventInterface {
        public function GetCaller() : CoreObjectInterface;
        public function GetMethodName() : string;
        public function GetArguments() : array;
        public function GetReturnValue();

        
    }
?>