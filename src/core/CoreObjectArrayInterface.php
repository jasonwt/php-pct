<?php
    declare(strict_types=1);

    namespace pct\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectArrayElementInterface;

    interface CoreObjectArrayInterface {
        /**
         * Get the int index of positionKey.  Null if not found
         * 
         * notFoundErrorCode sets the errorCode when a key is not found.
         * No error reporting if notFoundErrorCode == 0
         *
         * @param int|string|CoreObjectArrayElementInterface $positionKey
         * @param int $notFoundErrorCode
         * @return integer|null
         */
        public function Index($positionKey, int $notFoundErrorCode = 0) : ?int;

        /**
         * Checks if an existing element exists
         *
         * @param string|CoreObjectArrayElementInterface $element
         * @return boolean
         */
        public function Exists($element) : ?bool;

        /**
         * Get element keys that are derived from $isA
         *
         * @param string $isA
         * @return array
         */
        public function Keys(string $isA = "") : array;

        /**
         * Insert into the array
         *
         * @param CoreObjectArrayElementInterface $element
         * @param string $offset
         * @param int|string|CoreObjectArrayElementInterface $positionKey
         * @return CoreObjectArrayElementInterface|null
         */
        public function Insert(CoreObjectArrayElementInterface $element, string $offset = "", $positionKey = null) : ?CoreObjectArrayElementInterface;

        /**
         * Remove an array element
         *
         * @param int|string|CoreObjectArrayElementInterface $offset
         * @return CoreObjectArrayElementInterface|null
         */
        public function Remove($offset) : ?CoreObjectArrayElementInterface;

        /**
         * Add/Replace an array element
         *
         * @param null|int|string|CoreObjectArrayElementInterface $offset
         * @param CoreObjectArrayElementInterface $element
         * @return CoreObjectArrayElementInterface|null
         */
        public function Set($offset, CoreObjectArrayElementInterface $element) : ?CoreObjectArrayElementInterface;

        /**
         * Get Array value
         *
         * @param int|string|CoreObjectArrayElementInterface $offset
         * @return void
         */
        public function Get($offset);

        /**
         * Get array of any element matching $isA.
         * if $isA is "" or null all elements are returned
         *
         * @param string $isA
         * @return array
         */
        public function GetElements(string $isA) : array;
    }
?>