<?php
    declare(strict_types=1);

    namespace pct\core;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    use pct\core\CoreObjectArrayElementInterface;
    use pct\traits\ErrorTraits;

    class CoreObjectArrayElement implements CoreObjectArrayElementInterface {
        use ErrorTraits;

        public function __construct() {

        }

        

        
    }
?>