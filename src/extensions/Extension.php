<?php
    declare(strict_types=1);

    namespace pct\extensions;
    use pct\traits\core\CoreObjectComponentsTraitsInterface;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    
    use pct\core\CoreObject;
    use pct\extensions\ExtensionInterface;

    use pct\events\core\method\CoreMethodCallEvent;
    use pct\events\core\method\CoreMethodReturnEvent;

    

    class Extension extends CoreObject implements ExtensionInterface {
        private bool $enabled = true;

//        public function __construct($autoOffset = false, $componentsBaseClass = "", $extensionsBaseClass = "") {
  //          parent::__construct($autoOffset, $componentsBaseClass, $extensionsBaseClass);
    //    }

        

    }




?>