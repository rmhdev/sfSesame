<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new ProjectTestFunctional(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');

$browser->info('1. Display the create project form')->
    callGetActionNew()->
    with('request')->begin()->
        isParameter('module', 'project')->
        isParameter('action', 'new')->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
    end()
;

$browser->info("2. The required fields can't be empty")->
    callGetActionNew()->
    click('button-create', $browser->getDataForEmptyForm())->
    with('form')->begin()->
        hasErrors(2)->
    end()
;
    