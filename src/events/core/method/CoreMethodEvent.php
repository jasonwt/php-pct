<?php
    declare(strict_types=1);

    namespace pct\events\core\method;
    use pct\core\CoreInterface;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\events\core\CoreEvent;
    use pct\events\core\method\CoreMethodEventInterface;

    abstract class CoreMethodEvent extends CoreEvent implements CoreMethodEventInterface {
        
        private string $methodName = "";
        private array $arguments = [];
        

        /**
         * Undocumented function
         *
         * @param CoreInterface $caller
         * @param string $methodName
         * @param array $arguments
         * @param mixed $returnValue
         */
        public function __construct(CoreInterface $caller, string $methodName, array $arguments, $returnValue = null) {
            parent::__construct($caller, $returnValue);
            
            $this->methodName  = $methodName;
            $this->arguments   = $arguments;
            
        }

        

        /**
         * Undocumented function
         *
         * @return string
         */
        public function GetMethodName() : string {
            return $this->methodName;
        }

        /**
         * Undocumented function
         *
         * @return array
         */
        public function GetArguments() : array {
            return $this->arguments;
        }

        
        
        
    }

    
?>