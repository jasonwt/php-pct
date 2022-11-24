<?php
    declare(strict_types=1);

    namespace pct\components;
    use CompileError;
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once("vendor/autoload.php");

    use pct\components\Component;
    use function pct\debugging\dtprint;


    use pct\extensions\Extension;


    echo str_repeat("\n", 75);


    $parent = new Extension("ext");

    //$parent = new Component("com1");

    $child3 = new Component("child3");

    $parent->AddComponent(new Component("child1"));
    $parent->AddComponent($child3);
    $parent->AddComponent(new Component("child2"), $child3);
    $parent->AddComponent(new Component("child0"), "child1");
    $parent->AddComponent(new Component("child4"), "");

    $parent->ReplaceComponent($child3, new Component("new child 3"));
    $parent->RemoveComponent("new child 3");
    
    //dtprint($parent);
    

    interface AInterface {

    }

    class A implements AInterface {

    }

    interface BInterface extends AInterface {
        public function SayHI();

    }

    class B implements BInterface {
        public function SayHI() {
            echo "hi";
        }
    }

    $arr = [];

    $arr[] = new B();


    function dosomething (&$arr) : AInterface {
        return $arr[0];
    }


    $j = dosomething($arr);

    print_r($j->SayHI());
    

//
?>