<?php
    declare(strict_types=1);

    namespace pct\events\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectInterface;
    use pct\events\EventInterface;

    interface CoreEventInterface extends EventInterface {
        public function GetCaller() : CoreObjectInterface;
        public function GetReturnValue();
        public function SetReturnValue($returnValue);
    }
?>