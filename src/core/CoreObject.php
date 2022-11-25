<?php
    declare(strict_types=1);

    namespace pct\core;
    use pct\components\ComponentInterface;
    use pct\extensions\ExtensionInterface;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectArrayElement;

    use pct\events\core\CoreEventInterface;
    use pct\events\core\method\CoreMethodEventInterface;
    use pct\events\core\method\CoreMethodCallEventInterface;
    use pct\events\core\method\CoreMethodReturnEventInterface;
    use pct\events\core\method\CoreMethodCallEvent;
    use pct\events\core\method\CoreMethodReturnEvent;
    
    
    class CoreObject extends CoreObjectArrayElement implements CoreObjectInterface {
        public CoreObjectArray $components;
        public CoreObjectArray $extensions;

        private ?CoreObjectInterface $parent = null;

        public function __construct(bool $autoOffset = false, string $componentsBaseClass = null, string $extensionsBaseClass = null) {
            parent::__construct();

            if (($componentsBaseClass = trim(strval($componentsBaseClass))) == "")
                $componentsBaseClass = "\\pct\\components\\ComponentInterface";

            if (($componentsBaseClass = trim(strval($componentsBaseClass))) == "")
                $componentsBaseClass = "\\pct\\components\\ExtensionInterface";

            $this->components = new CoreObjectArray($componentsBaseClass, $autoOffset);
            $this->extensions = new CoreObjectArray($extensionsBaseClass, $autoOffset);
        }
       
        public function GetParent() : ?CoreObjectInterface {
            return $this->parent;
        }
        
        public function SetParent(?CoreObjectInterface $parent) : ?bool {
            $returnValue = null;

            $db = debug_backtrace();

            if (count($db) > 2) {
                if ($db[0]["object"] == $this && $db[0]["object"] == $db[2]["object"] && $db[0]["function"] == $db[2]["function"])
                    return true;
            }

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$parent]))) {

                if (count($db) > 1) {
                    if ($db[1]["function"] == "AddComponent" || $db[1]["function"] == "RemoveComponent") {
                        if (!is_null($this->GetParent())) {
                            if ($this instanceof ComponentInterface)
                                $returnValue = ($this->GetParent()->RemoveComponent($this) == $this);
//                            else if ($this instanceof ExtensionInterface)
//                                $returnValue = $this->GetParent()->RemoveExtension($this);
        
                            if (is_null($returnValue))
                                $this->errors = array_merge($this->errors, $this->components->GetErrors());
                            else
                                $this->parent = $parent;
                            
                        } else {
                            $this->parent = $parent;
                        }
                    } else {
                        $this->RegisterError(E_USER_ERROR, "SetParent can only be called from AddComponent, AddExtension, RemoveComponent and RemoveExtension");    
                    }
                } else {
                    $this->RegisterError(E_USER_ERROR, "SetParent can only be called from AddComponent, AddExtension, RemoveComponent and RemoveExtension");
                }
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$parent], $returnValue));           
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

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$component])))
                if (is_null($returnValue = $this->components->Index($component, 0)))
                    $this->errors = array_merge($this->errors, $this->components->GetErrors());

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));
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

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$component])))
                if (is_null($returnValue = $this->components->Exists($component, 0)))
                    $this->errors = array_merge($this->errors, $this->components->GetErrors());

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));
        }

        /**
         * Get the name of components that a derived from $isA
         *
         * @param string $isA
         * @return array
         */
        public function ComponentNames(string $isA) : array {
            $returnValue = array();

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->components->Keys($isA);                

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
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

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$component, &$name, &$position]))) {

                if (!is_null($this->components->Insert($component, $name, $position)))
                    if ($component->SetParent($this))
                        $returnValue = $this;
            
                if (is_null($returnValue))
                    $this->errors = array_merge($this->errors, $this->components->GetErrors());
                    
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));
        }

        /**
         * Remove a child component
         *
         * @param int|string|CoreObjectInterface $component
         * @return CoreObjectInterface|null
         */
        public function RemoveComponent($component) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$component]))) {

                if (!is_null($this->components->Remove($component)))
                    if (!is_null($component->SetParent(null)))
                        $returnValue = $this;

                if (is_null($returnValue))
                    $this->errors = array_merge($this->errors, $this->components->GetErrors());

            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$component], $returnValue));    
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

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$offset, &$component]))) {
                if (!is_null($returnValue = $this->components->Set($offset, $component)))
                    $returnValue = ($component->SetParent($this) ? $this : null);                    
                else
                    $this->errors = array_merge($this->errors, $this->components->GetErrors());
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$offset, &$component], $returnValue));
        }

        /**
         * Get a component value
         *
         * @param int|string|ComponentInterface $offset
         * @return null|CoreObjectInterface
         */
        public function GetComponent($offset) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$offset]))) {
                if (is_null($returnValue = $this->components->Get($offset)))                    
                    $this->errors = array_merge($this->errors, $this->components->GetErrors());
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$offset], $returnValue));
        }

        /**
         * Get all components that are dervied by $isA
         *
         * @param string $isA
         * @return array
         */
        public function GetComponents(string $isA) : array {
            $returnValue = array();

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->components->GetElements($isA);

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
        }
























        protected function SendEvent(CoreMethodEventInterface $event) {
            return $event->GetReturnValue();
        }

        protected function HandleEvent(CoreEventInterface $event) {

        }


        
    }




?>