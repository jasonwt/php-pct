<?php
    declare(strict_types=1);

    namespace pct\core;
    use pct\components\ComponentInterface;
    use pct\extensions\ExtensionInterface;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectArrayElement;
    
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

            if (count($db) > 1) {
                if ($db[1]["function"] == "AddComponent" || $db[1]["function"] == "RemoveComponent") {
                    if (!is_null($this->GetParent())) {            

                        if ($this instanceof ComponentInterface)
                            $returnValue = $this->GetParent()->RemoveComponent($this);
                        //else if ($this instanceof ExtensionInterface)
                        //    $returnValue = $this->parent->RemoveExtension($this);
    
                        if (is_null($returnValue)) {
                            $this->errors = array_merge($this->errors, $this->components->GetErrors());
                            return null;
                        }
                    }
    
                    $this->parent = $parent;
    
                    return true;
                } else {
                    $this->RegisterError(E_USER_ERROR, "SetParent can only be called from AddComponent, AddExtension, RemoveComponent and RemoveExtension");
                    return null;
                }
            } else {
                $this->RegisterError(E_USER_ERROR, "SetParent can only be called from AddComponent and RemoveComponent");
            }

            return false;            
        }

        public function AddComponent(ComponentInterface $component, string $name = "", $position = null) : ?CoreObjectInterface {
            $returnValue = null;

            if (!is_null($this->components->Insert($component, $name, $position)))
                if ($component->SetParent($this))
                    $returnValue = $this;
          
            if (is_null($returnValue))
                $this->errors = array_merge($this->errors, $this->components->GetErrors());

            return $returnValue;
        }

        public function RemoveComponent($component) : ?CoreObjectInterface {
            $returnValue = null;

            if (!is_null($this->components->Remove($component)))
                if (!is_null($component->SetParent(null)))
                    $returnValue = $this;

            if (is_null($returnValue))
                $this->errors = array_merge($this->errors, $this->components->GetErrors());
    
            return $returnValue;
        }


        
    }




?>