<?php
    declare(strict_types=1);

    namespace pct\core;
    use ArrayObject;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectArrayInterface;
    use pct\traits\ErrorTraits;
    use pct\core\CoreObjectArrayElementInterface;

    class CoreObjectArray implements CoreObjectArrayInterface {
        use ErrorTraits;

        private $baseClass;
        private $autoOffset;
        private array $elements = [];

        public function __construct(string $baseClass = null, bool $autoOffset = false) {
            if (($this->baseClass = trim(strval($baseClass))) == "")
                $this->baseClass = "\\pct\\core\\CoreObjectArrayElementInterface";
            
            //$this->RegisterError(E_USER_ERROR, "baseClass must not be an empty string");

            $this->autoOffset = $autoOffset;
        }

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
        public function Index($positionKey, int $notFoundErrorCode = 0) : ?int {
            $elementKeys = array_keys($this->elements);
            $positionIndex = null;

            if (is_null($positionKey)) {
                if ($notFoundErrorCode)
                    $this->RegisterError($notFoundErrorCode, "positionKey must not be null.");

                return null;
            }

            if (is_string($positionKey)) {
                if (($positionKey = trim($positionKey)) != "") {
                    if (($positionIndex = array_search($positionKey, $elementKeys)) === false) {
                        if ($notFoundErrorCode)
                            $this->RegisterError($notFoundErrorCode, "string key positionKey '$positionKey' does not exist.");

                        return null;
                    } else {
                        return $positionIndex;
                    }
                } else {
                    if ($notFoundErrorCode)
                        $this->RegisterError(E_USER_ERROR, "string key positionKey must not be empty.");

                    return null;                    
                }
            }

            if (is_int($positionKey)) {
                $positionIndex = $positionKey;

                if ($positionIndex < 0)
                    $positionIndex += count($elementKeys);

                if ($positionIndex < 0 || $positionIndex >= count($elementKeys)) {
                    if ($notFoundErrorCode)
                        $this->RegisterError($notFoundErrorCode, "int key positionKey '$positionKey' does not exist.");

                    return null;
                } else {
                    return $positionIndex;
                }
            }

            if (is_object($positionKey)) {
                if (is_a($positionKey, $this->baseClass)) {
                    if (($positionIndex = array_search($positionKey, $this->elements, true)) === false) {
                        if ($notFoundErrorCode)
                            $this->RegisterError($notFoundErrorCode, "object key positionKey does not exist.");

                        return null;
                    } else {
                        return array_search($positionIndex, array_keys($this->elements));
                    };
                }
            }
            
            $this->RegisterError(E_USER_ERROR, "invalid type for positionKey '" . gettype($positionKey) . "'. Expecting string or object derived from CoreObjectArrayElementInterface.");
            return null;   
        }

        /**
         * Checks if an existing element exists
         *
         * @param string|CoreObjectArrayElementInterface $element
         * @return boolean
         */
        public function Exists($element) : ?bool {
            if (is_string($element) || (is_object($element) && is_a($element, $this->baseClass)))
                return (!is_null($this->Index($element)));

            $this->RegisterError(E_USER_ERROR, "invalid type for element '" . gettype($element) . "'. Expecting string or object derived from CoreObjectArrayElementInterface.");
            return null;
        }

        /**
         * Get element keys that are derived from $isA
         *
         * @param string $isA
         * @return array
         */
        public function Keys(string $isA = "") : array {
            return array_keys($this->GetElements($isA));
        }

        /**
         * Insert into the array
         *
         * @param CoreObjectArrayElementInterface $element
         * @param string $offset
         * @param int|string|CoreObjectArrayElementInterface $positionKey
         * @return CoreObjectArrayElementInterface|null
         */
        public function Insert(CoreObjectArrayElementInterface $element, string $offset = "", $positionKey = null) : ?CoreObjectArrayElementInterface {
            $positionIndex = (is_null($positionKey) ? null : $this->Index($positionKey, E_USER_ERROR));
            
            if (($offset = trim($offset)) == "") {
                if (!$this->autoOffset) {
                    $this->RegisterError(E_USER_ERROR, "offset must not be empty.");
                    return null;
                }

                $cnt = count($this->elements);

                for (;isset($this->elements[$cnt]); $cnt ++);

                $offset = strval($cnt);
            }

            if (isset($this->elements[$offset])) {
                $this->RegisterError(E_USER_ERROR, "offset already exists.");
                return null;
            }

            if (in_array($element, $this->elements, true) !== false) {
                $this->RegisterError(E_USER_ERROR, "element already exists.");
                return null;
            }

            if (is_null($positionKey)) {
                $this->elements[$offset] = $element;

            } else if ($positionIndex == 0) {
                $this->elements = array_merge(
                    [$offset => $element],
                    $this->elements
                );

            } else {
                $this->elements = array_merge(
                    array_slice($this->elements, 0, $positionIndex),
                    [$offset => $element],
                    array_slice($this->elements, $positionIndex)
                );
            }

            return $element;
        }

        /**
         * Remove an array element
         *
         * @param int|string|CoreObjectArrayElementInterface $offset
         * @return CoreObjectArrayElementInterface|null
         */
        public function Remove($offset) : ?CoreObjectArrayElementInterface {
            if (is_null($offsetIndex = $this->Index($offset, E_USER_ERROR)))
                return null;

            $offsetName = array_keys($this->elements)[$offsetIndex];

            $element = $this->elements[$offsetName];

            unset ($this->elements[$offsetName]);            
            
            return $element;
        }

        /**
         * Add/Replace an array element
         *
         * @param null|int|string|CoreObjectArrayElementInterface $offset
         * @param CoreObjectArrayElementInterface $element
         * @return CoreObjectArrayElementInterface|null
         */
        public function Set($offset, CoreObjectArrayElementInterface $element) : ?CoreObjectArrayElementInterface {
            if (is_string($offset) || is_null($offset)) {
                if (($offset = trim(strval($offset))) == "") {                    
                    $this->RegisterError(E_USER_ERROR, "offset must not be empty.");
                    return null;                    
                }
            } else {
                if (is_null($offsetIndex = $this->Index($offset))) {
                    $this->RegisterError(E_USER_ERROR, "invalid offset.");
                    return null;
                }
                    
                $offset = array_keys($this->elements)[$offsetIndex];                
            }

            if (in_array($element, $this->elements, true) !== false) {
                $this->RegisterError(E_USER_ERROR, "element already exists.");
                return null;
            }

            $this->elements[$offset] = $element;

            return $element;
        }

        /**
         * Get Array value
         *
         * @param int|string|CoreObjectArrayElementInterface $offset
         * @return mixed
         */
        public function Get($offset) {
            if (is_null($offsetIndex = $this->Index($offset, E_USER_ERROR)))
                return null;

            return $this->elements[array_keys($this->elements)[$offsetIndex]];
        }

        /**
         * Get array of any element matching is_a($isA).
         * if $isA is "" or null all elements are returned
         *
         * @param string $isA
         * @return array
         */
        public function GetElements(string $isA) : array {
            $returnValue = $this->elements;

            if (($isA = trim(strval($isA))) != "" ) {
                $returnValue = array_filter($returnValue, function ($v, $k) use ($isA) {
                    return is_a($v, $isA);
                }, ARRAY_FILTER_USE_BOTH);
            }

            return $returnValue;
        }

    }
    
?>