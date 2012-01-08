<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  info('1. Display the create company form')->
  get('/company/create')->

  with('request')->begin()->
    isParameter('module', 'company')->
    isParameter('action', 'create')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;

$browser->
    info("2. The required fields can't be empty " )->
    get('/company/create')->
    click('create', array())->
      with('form')->begin()->
        hasErrors(1)
;