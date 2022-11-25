<?php
    declare(strict_types=1);

    namespace pct\events\core\method;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectInterface;
    use pct\events\core\method\CoreMethodEvent;
    use pct\events\core\method\CoreMethodReturnEventInterface;

    class CoreMethodReturnEvent extends CoreMethodEvent implements CoreMethodReturnEventInterface {
        public function __construct(CoreObjectInterface $caller, string $methodName, array $arguments, $returnValue = null) {
            parent::__construct($caller, $methodName, $arguments, $returnValue);            
        }

        static public function AUTO(array $arguments, $returnValue = null) : ?CoreMethodReturnEventInterface {
            $backtrace = debug_backtrace()[1];

            return new CoreMethodReturnEvent($backtrace["object"], $backtrace["function"], $arguments, $returnValue);
        }
    }
?>