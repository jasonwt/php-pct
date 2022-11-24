<?php
    declare(strict_types=1);

    namespace pct\components;
    use CompileError;
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once("vendor/autoload.php");

    use pct\components\Component;
    use pct\extensions\Extension;
    use function pct\debugging\dtprint;




    echo str_repeat("\n", 75);


    $parent = new Component("com1");
    $child1 = new Component("child1");
    $parent->AddComponent($child1);
    $parent->RemoveComponent($child1);
    $parent->AddComponent($child1);

    $child3 = new Component("child3");
    $parent->AddComponent($child3);
    $parent->AddComponent(new Component("child2"), $child3);
    
    $parent->AddComponent(new Component("child0"), "child1");
    $parent->AddComponent(new Component("child4"), "");

    $parent->ReplaceComponent($child3, new Component("new child 3"));
    $parent->RemoveComponent("child1");

    //$parent->RemoveComponent(new Extension("extension"));
    
    dtprint($parent);
    

    

//
?>