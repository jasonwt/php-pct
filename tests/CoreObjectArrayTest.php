<?php
    declare(strict_types=1);

    namespace pct\components;
    use CompileError;
    
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once("vendor/autoload.php");

    use function pct\debugging\dtprint;

    use pct\core\CoreObjectArray;
    use pct\core\CoreObjectArrayInterface;

    use pct\core\CoreObjectArrayElement;
    use pct\core\CoreObjectArrayElementInterface;

    use pct\core\CoreObject;
    use pct\components\Component;

    $parent = new Component(true);
    $parent2 = new Component(true);

    $parent->AddComponent(($child1 = new Component()));

    $parent2->AddComponent($child1);
    //$parent->AddComponent("child1", ($child1 = new Component()));
    //$parent2->AddComponent("child1", $child1);
    //$child1->SetParent(null);
    //$parent->components->Set("child1", new CoreObject());
    //$parent->components->Get("child1")->components->Set("child11", new CoreObject());
    //$parent->components->Get("child1")->components->Get("child11")->components->Set("child111", new CoreObjectArrayElement());


    dtprint("parent:\n", $parent, "parent2:\n", $parent2, "child:\n", $child1);
/*    
    $coreObjectArray = new CoreObjectArray(null, true);
    
    $coreObjectArray->Insert("client4", ($client4 = new CoreObjectArrayElement()));
    $coreObjectArray->Insert("client3", ($client3 = new CoreObjectArrayElement()), "client4");
    $coreObjectArray->Insert("client2", ($client2 = new CoreObjectArrayElement()), $client3);
    $coreObjectArray->Insert("client1", ($client1 = new CoreObjectArrayElement()), 0);
    $coreObjectArray->Insert("client5", ($client5 = new CoreObjectArrayElement()));
    $coreObjectArray->Insert("client6", ($client6 = new CoreObjectArrayElement()));
    $coreObjectArray->Insert("client7", ($client7 = new CoreObjectArrayElement()));

    $coreObjectArray->Remove("client3");
    $coreObjectArray->Remove(1);
    $coreObjectArray->Remove(-1);
    $coreObjectArray->Remove($client4);

    $coreObjectArray->Set(null, ($client7 = new Component()));


    dtprint("get: ", $coreObjectArray->Get("3"));


    dtprint("coreObjectArray: ", $coreObjectArray);
*/    
?>