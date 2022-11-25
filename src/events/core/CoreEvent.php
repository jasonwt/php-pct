<?php
    declare(strict_types=1);

    namespace pct\events\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    
    use pct\core\CoreObjectInterface;
    use pct\events\Event;


    abstract class CoreEvent extends Event implements CoreEventInterface {
        private ?CoreObjectInterface $caller = null;
        private $returnValue = null;

        public function __construct(CoreObjectInterface $caller, $returnValue = null) {
            $this->caller      = $caller;
            $this->returnValue = $returnValue;
        }

        public function GetCaller() : CoreObjectInterface {
            return $this->caller;
        }

        public function GetReturnValue() {
            return $this->returnValue;
        }

        public function SetReturnValue($returnValue) {
            $this->returnValue = $returnValue;
        }
    }
?>