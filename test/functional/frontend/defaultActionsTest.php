<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/default/index')->

  with('request')->begin()->
    isParameter('module', 'default')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '!/This is a temporary page/')->
  end()
;

$browser->
    info('Personalized 404 page')->
    get('/404')->
    with('request')->begin()->
        isParameter('module', 'default')->
        isParameter('action', '404')->
    end()->
    
    with('response')->begin()->
        isStatusCode(404)->
        checkElement('.content', '#404#')->
    end()->
    
    get('/qwertyuiop')->
    with('response')->begin()->
        isStatusCode(404)->
    end()
;
