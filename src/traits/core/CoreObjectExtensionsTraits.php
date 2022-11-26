<?php
    declare(strict_types=1);

    namespace pct\traits\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectArray;
    use pct\core\CoreObject;
    use pct\core\CoreObjectInterface;
    use pct\extensions\ExtensionInterface;

    use pct\events\core\method\CoreMethodCallEvent;
    use pct\events\core\method\CoreMethodReturnEvent;
    
    trait CoreObjectExtensionsTraits {
        private CoreObjectArray $extensions;
        private CoreObject $thisComponent;

        public function __construct(CoreObject $thisComponent, bool $autoOffset = false, string $baseClass = "") {
            $this->thisComponent = $thisComponent;

            if (($baseClass = trim(strval($baseClass))) == "")
                $baseClass = "\\pct\\extensions\\ExtensionInterface";

            $this->extensions = new CoreObjectArray($baseClass, $autoOffset);
        }
        
        /**
         * Get the integer index of the specified extension/key
         * null if not found
         *
         * @param null|int|string|ExtensionInterface $extension
         * @return integer|null
         */
        public function ExtensionIndex($extension) : ?int {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$extension])))
                if (is_null($returnValue = $this->extensions->Index($extension, 0)))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->extensions->GetErrors());

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));
        }

        /**
         * Check if a extension/key exists
         * null on error
         *
         * @param null|int|string|ExtensionInterface $extension
         * @return integer|null
         */
        public function ExtensionExists($extension) : ?bool {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$extension])))
                if (is_null($returnValue = $this->extensions->Exists($extension)))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->extensions->GetErrors());

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));
        }

        /**
         * Get the name of extensions that a derived from $isA
         *
         * @param string $isA
         * @return array
         */
        public function ExtensionNames(string $isA) : array {
            $returnValue = array();

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->extensions->Keys($isA);                

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
        }
        
        /**
         * Add A child extension
         *
         * @param ExtensionInterface $extension
         * @param string $name
         * @param null|int|string|CoreObjectInterface $position
         * @return CoreObjectInterface|null
         */
        public function AddExtension(ExtensionInterface $extension, string $name = "", $position = null) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$extension, &$name, &$position]))) {

                if (!is_null($this->extensions->Insert($extension, $name, $position)))
                    if ($extension->SetParent($this->thisComponent))
                        $returnValue = $this;
            
                if (is_null($returnValue))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->extensions->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));
        }

        /**
         * Remove a child extension
         *
         * @param int|string|CoreObjectInterface $extension
         * @return CoreObjectInterface|null
         */
        public function RemoveExtension($extension) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$extension]))) {

                if (!is_null($this->extensions->Remove($extension)))
                    if (!is_null($extension->SetParent(null)))
                        $returnValue = $this;

                if (is_null($returnValue))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->extensions->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));    
        }

        /**
         * Add/Replace a extension
         *
         * @param int|string|ExtensionInterface $offset
         * @param ExtensionInterface $extension
         * @return CoreObjectInterface|null
         */
        public function SetExtension($offset, ExtensionInterface $extension) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$offset, &$extension]))) {
                if (!is_null($returnValue = $this->extensions->Set($offset, $extension)))
                    $returnValue = ($extension->SetParent($this->thisComponent) ? $this : null);                    
                else
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->extensions->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$offset, &$extension], $returnValue));
        }

        /**
         * Get a extension value
         *
         * @param int|string|ExtensionInterface $offset
         * @return null|CoreObjectInterface
         */
        public function GetExtension($offset) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$offset]))) {
                if (is_null($returnValue = $this->extensions->Get($offset)))                    
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->extensions->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$offset], $returnValue));
        }

        /**
         * Get all extensions that are dervied by $isA
         *
         * @param string $isA
         * @return array
         */
        public function GetExtensions(string $isA) : array {
            $returnValue = array();

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->extensions->GetElements($isA);

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
        }

    }
?>