<?php
    declare(strict_types=1);

    namespace pct\events\core\method;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectInterface;
    use pct\events\core\CoreEvent;
    use pct\events\core\method\CoreMethodEventInterface;

    abstract class CoreMethodEvent extends CoreEvent implements CoreMethodEventInterface {
        
        private string $methodName = "";
        private array $arguments = [];
        
        public function __construct(CoreObjectInterface $caller, string $methodName, array $arguments, $returnValue = null) {
            parent::__construct($caller, $returnValue);
            
            $this->methodName  = $methodName;
            $this->arguments   = $arguments;
        }

        public function GetMethodName() : string {
            return $this->methodName;
        }

        public function GetArguments() : array {
            return $this->arguments;
        }   
    }
?>