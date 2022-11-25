<?php
    declare(strict_types=1);

    namespace pct\traits;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\debugging\Debugging;

    trait ErrorTraits {
        protected array $errors = [];

        /**
         * Register new error
         * 
         * @param int $errorCode
         * @param string $errorMessage
         * @return bool
         */
        protected function RegisterError(int $errorCode, string $errorMessage) : bool {
            $errorMessage = Debugging::DString(2, 0, [$errorMessage]);

            if ($errorCode == E_USER_NOTICE || $errorCode == E_USER_WARNING) {
                $this->errors[] = ["errorCode" => $errorCode, "errorMessage" => $errorMessage];

                return true;
            }

            throw new \ErrorException($errorMessage, $errorCode);
        }

        /**
         * Clear all registered errors
         *
         * @return void
         */
        public function ClearErrors() {
            $this->errors = [];
        }   

        /**
         * Get next errorMessage, null when no errors are remaining
         *
         * @return string|null
         */
        public function GetError() : ?string {
            return array_shift($this->errors);
        }

        public function GetErrors() : array {
            $returnValue = $this->errors;

            $this->errors = [];

            return $returnValue;
        }
    }
    
?>