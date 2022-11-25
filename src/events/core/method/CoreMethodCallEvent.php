<?php
    declare(strict_types=1);

    namespace pct\events\core\method;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectInterface;
    use pct\events\core\method\CoreMethodEvent;
    use pct\events\core\method\CoreMethodCallEventInterface;

    class CoreMethodCallEvent extends CoreMethodEvent implements CoreMethodCallEventInterface {
        public function __construct(CoreObjectInterface $caller, string $methodName, array $arguments) {
            parent::__construct($caller, $methodName, $arguments, true);            
        }

        static public function AUTO(array $arguments) : ?CoreMethodCallEventInterface {
            $backtrace = debug_backtrace()[1];

            return new CoreMethodCallEvent($backtrace["object"], $backtrace["function"], $arguments);
        }
    }
?>