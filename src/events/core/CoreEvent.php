<?php
    declare(strict_types=1);

    namespace pct\events\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    
    use pct\core\CoreInterface;
    use pct\events\Event;


    abstract class CoreEvent extends Event implements CoreEventInterface {
        private ?CoreInterface $caller = null;
        private $returnValue = null;

        public function __construct(CoreInterface $caller, $returnValue = null) {
            $this->caller      = $caller;
            $this->returnValue = $returnValue;
        }

        /**
         * Undocumented function
         *
         * @return CoreInterface
         */
        public function GetCaller() : CoreInterface {
            return $this->caller;
        }

        /**
         * Undocumented function
         *
         * @return mixed
         */
        public function GetReturnValue() {
            return $this->returnValue;
        }

        /**
         * Undocumented function
         *
         * @param [type] $returnValue
         * @return void
         */
        public function SetReturnValue($returnValue) {
            $this->returnValue = $returnValue;
        }
    }
?>