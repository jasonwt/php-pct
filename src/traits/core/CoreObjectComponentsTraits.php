<?php
    declare(strict_types=1);

    namespace pct\traits\core;
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectArray;
    
    use pct\core\CoreObject;
    use pct\core\CoreObjectInterface;
    use pct\components\ComponentInterface;

    use pct\events\core\method\CoreMethodCallEvent;
    use pct\events\core\method\CoreMethodReturnEvent;
    
    trait CoreObjectComponentsTraits {
        private CoreObjectArray $components;
        private CoreObject $thisComponent;

        public function __construct(CoreObject $thisComponent, bool $autoOffset = false, string $baseClass = "") {
            $this->thisComponent = $thisComponent;

            if (($baseClass = trim(strval($baseClass))) == "")
                $baseClass = "\\pct\\components\\ComponentInterface";

            $this->components = new CoreObjectArray($baseClass, $autoOffset);
        }

        /**
         * Get the integer index of the specified component/key
         * null if not found
         *
         * @param null|int|string|ComponentInterface $component
         * @return integer|null
         */
        public function ComponentIndex($component) : ?int {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$component])))
                if (is_null($returnValue = $this->components->Index($component, 0)))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->components->GetErrors());

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));
        }

        /**
         * Check if a component/key exists
         * null on error
         *
         * @param null|int|string|ComponentInterface $component
         * @return integer|null
         */
        public function ComponentExists($component) : ?bool {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$component])))
                if (is_null($returnValue = $this->components->Exists($component)))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->components->GetErrors());

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));
        }

        /**
         * Get the name of components that a derived from $isA
         *
         * @param string $isA
         * @return array
         */
        public function ComponentNames(string $isA) : array {
            $returnValue = array();

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->components->Keys($isA);                

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
        }
        
        /**
         * Add A child component
         *
         * @param ComponentInterface $component
         * @param string $name
         * @param null|int|string|CoreObjectInterface $position
         * @return CoreObjectInterface|null
         */
        public function AddComponent(ComponentInterface $component, string $name = "", $position = null) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$component, &$name, &$position]))) {

                if (!is_null($this->components->Insert($component, $name, $position)))
                    if ($component->SetParent($this->thisComponent))
                        $returnValue = $this;
            
                if (is_null($returnValue))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->components->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));
        }

        /**
         * Remove a child component
         *
         * @param int|string|CoreObjectInterface $component
         * @return CoreObjectInterface|null
         */
        public function RemoveComponent($component) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$component]))) {

                if (!is_null($this->components->Remove($component)))
                    if (!is_null($component->SetParent(null)))
                        $returnValue = $this;

                if (is_null($returnValue))
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->components->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));    
        }

        /**
         * Add/Replace a component
         *
         * @param int|string|ComponentInterface $offset
         * @param ComponentInterface $component
         * @return CoreObjectInterface|null
         */
        public function SetComponent($offset, ComponentInterface $component) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$offset, &$component]))) {
                if (!is_null($returnValue = $this->components->Set($offset, $component)))
                    $returnValue = ($component->SetParent($this->thisComponent) ? $this : null);                    
                else
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->components->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$offset, &$component], $returnValue));
        }

        /**
         * Get a component value
         *
         * @param int|string|ComponentInterface $offset
         * @return null|CoreObjectInterface
         */
        public function GetComponent($offset) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$offset]))) {
                if (is_null($returnValue = $this->components->Get($offset)))                    
                    $this->thisComponent->errors = array_merge($this->thisComponent->errors, $this->components->GetErrors());
            }

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$offset], $returnValue));
        }

        /**
         * Get all components that are dervied by $isA
         *
         * @param string $isA
         * @return array
         */
        public function GetComponents(string $isA) : array {
            $returnValue = array();

            if ($this->thisComponent->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->components->GetElements($isA);

            return $this->thisComponent->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
        }
    }
?>