<?php
    declare(strict_types=1);

    namespace pct\events\core\method;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\events\core\method\CoreMethodEventInterface;

    interface CoreMethodReturnEventInterface extends CoreMethodEventInterface {
        static public function AUTO(array $arguments, $returnValue = null) : ?CoreMethodReturnEventInterface;
    }
    
?>