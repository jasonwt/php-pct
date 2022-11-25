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

        /**
         * Constructor
         *
         * @param boolean $autoOffset
         * @param string|null $componentsBaseClass
         * @param string|null $extensionsBaseClass
         */
        public function __construct(bool $autoOffset = false, string $componentsBaseClass = null, string $extensionsBaseClass = null) {
            parent::__construct();

            if (($componentsBaseClass = trim(strval($componentsBaseClass))) == "")
                $componentsBaseClass = "\\pct\\components\\ComponentInterface";

            if (($componentsBaseClass = trim(strval($componentsBaseClass))) == "")
                $componentsBaseClass = "\\pct\\components\\ExtensionInterface";

            $this->components = new CoreObjectArray($componentsBaseClass, $autoOffset);
            $this->extensions = new CoreObjectArray($extensionsBaseClass, $autoOffset);
        }
       
        /**
         * Get Parent
         *
         * @return CoreObjectInterface|null
         */
        public function GetParent() : ?CoreObjectInterface {
            return $this->parent;
        }
        
        /**
         * Set the parent.
         * Only available to AddComponent, RemoveComponent functions
         *
         * @param CoreObjectInterface|null $parent
         * @return boolean|null
         */
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
                            else if ($this instanceof ExtensionInterface)
                                $returnValue = $this->GetParent()->RemoveExtension($this);
        
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
                if (is_null($returnValue = $this->components->Exists($component)))
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














        /**
         * Get the integer index of the specified extension/key
         * null if not found
         *
         * @param null|int|string|ExtensionInterface $extension
         * @return integer|null
         */
        public function ExtensionIndex($extension) : ?int {
            $returnValue = null;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$extension])))
                if (is_null($returnValue = $this->extensions->Index($extension, 0)))
                    $this->errors = array_merge($this->errors, $this->extensions->GetErrors());

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));
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

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$extension])))
                if (is_null($returnValue = $this->extensions->Exists($extension)))
                    $this->errors = array_merge($this->errors, $this->extensions->GetErrors());

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));
        }

        /**
         * Get the name of extensions that a derived from $isA
         *
         * @param string $isA
         * @return array
         */
        public function ExtensionNames(string $isA) : array {
            $returnValue = array();

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->extensions->Keys($isA);                

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
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

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$extension, &$name, &$position]))) {

                if (!is_null($this->extensions->Insert($extension, $name, $position)))
                    if ($extension->SetParent($this))
                        $returnValue = $this;
            
                if (is_null($returnValue))
                    $this->errors = array_merge($this->errors, $this->extensions->GetErrors());
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));
        }

        /**
         * Remove a child extension
         *
         * @param int|string|CoreObjectInterface $extension
         * @return CoreObjectInterface|null
         */
        public function RemoveExtension($extension) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$extension]))) {

                if (!is_null($this->extensions->Remove($extension)))
                    if (!is_null($extension->SetParent(null)))
                        $returnValue = $this;

                if (is_null($returnValue))
                    $this->errors = array_merge($this->errors, $this->extensions->GetErrors());
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$extension], $returnValue));    
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

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$offset, &$extension]))) {
                if (!is_null($returnValue = $this->extensions->Set($offset, $extension)))
                    $returnValue = ($extension->SetParent($this) ? $this : null);                    
                else
                    $this->errors = array_merge($this->errors, $this->extensions->GetErrors());
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$offset, &$extension], $returnValue));
        }

        /**
         * Get a extension value
         *
         * @param int|string|ExtensionInterface $offset
         * @return null|CoreObjectInterface
         */
        public function GetExtension($offset) : ?CoreObjectInterface {
            $returnValue = null;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$offset]))) {
                if (is_null($returnValue = $this->extensions->Get($offset)))                    
                    $this->errors = array_merge($this->errors, $this->extensions->GetErrors());
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$offset], $returnValue));
        }

        /**
         * Get all extensions that are dervied by $isA
         *
         * @param string $isA
         * @return array
         */
        public function GetExtensions(string $isA) : array {
            $returnValue = array();

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$isA])))
                $returnValue = $this->extensions->GetElements($isA);

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$isA], $returnValue));
        }
















        protected function SendEvent(CoreMethodEventInterface $event) {
            return $event->GetReturnValue();
        }

        protected function HandleEvent(CoreEventInterface $event) {

        }
    }
?>