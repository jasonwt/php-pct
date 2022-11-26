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
    use pct\events\core\method\CoreMethodCallEvent;
    use pct\events\core\method\CoreMethodReturnEvent;

    use pct\traits\core\CoreObjectComponentsTraits;
    use pct\traits\core\CoreObjectExtensionsTraits;
    use pct\core\CoreObjectInterface;
    
    
    class CoreObject extends CoreObjectArrayElement implements CoreObjectInterface {
        private bool $enabled = true;

        use CoreObjectComponentsTraits {
            __construct as CoreObjectComponentTraitsContrustor;
        }
        use CoreObjectExtensionsTraits {
            __construct as CoreObjectExtensionTraitsContrustor;
        }

        private ?CoreObjectInterface $parent = null;

        /**
         * Constructor
         *
         * @param boolean $autoOffset
         * @param string|null $componentsBaseClass
         * @param string|null $extensionsBaseClass
         */
        public function __construct(bool $autoOffset = false, string $componentsBaseClass = "", string $extensionsBaseClass = "") {
            parent::__construct();

            $this->CoreObjectComponentTraitsContrustor($this, $autoOffset, $componentsBaseClass);            
            $this->CoreObjectComponentTraitsContrustor($this, $autoOffset, $extensionsBaseClass);
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

        public function Disabled() : bool {
            $returnValue = false;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([])) !== false) {
                if ($this->enabled) {
                    $returnValue = true;
                    $this->enabled = false;                    
                } else {
                    $this->RegisterError(E_USER_WARNING, "Disable() failed.  Already disabled.");
                }
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([], $returnValue));
        }


        public function Enable() : bool {
            $returnValue = false;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([])) !== false) {
                if (!$this->enabled) {
                    $returnValue = true;
                    $this->enabled = true;                    
                } else {
                    $this->RegisterError(E_USER_WARNING, "Enable() failed.  Already disabled.");
                }
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([], $returnValue));
        }

        public function IsEnabled() : bool {
            $returnValue = false;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([])) !== false) {
                if (!$this->enabled) {
                    $returnValue = true;
                    $this->enabled = true;                    
                } else {
                    $this->RegisterError(E_USER_WARNING, "Enable() failed.  Already disabled.");
                }
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([], $returnValue));
        }

        /**
         * See if we or any of our extensions can call the specified methodName
         *
         * @param string $methodName
         * @return boolean
         */
/*        
        public function CanCall(string $methodName): bool {
            $returnValue = false;

            if ($this->SendEvent(CoreMethodCallEvent::AUTO([&$methodName]))) {
//                if (method_exists($this, $methodName))
  //                  $returnValue = (new \ReflectionMethod($this, $methodName))->isPublic();
    
    //            foreach ($this->extensions as $extension)
      //              $returnValue = $returnValue | $extension->CanExtensionCall($methodName,1);
            }

            return $this->SendEvent(CoreMethodReturnEvent::AUTO([&$methodName], $returnValue));
        }
*/

        /**
         * Send an event
         *
         * @param CoreMethodEventInterface $event
         * @return mixed
         */
        protected function SendEvent(CoreMethodEventInterface $event) {           
            return $event->GetReturnValue();
        }

        protected function HandleEvent(CoreEventInterface $event) {

        }
    }
?>