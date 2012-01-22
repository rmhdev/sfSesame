<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new CompanyTestFunctional(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');

$browser->
  info('1. Display the create company form')->
  get('/company/new')->

  with('request')->begin()->
    isParameter('module', 'company')->
    isParameter('action', 'new')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;

$dataEmpty = array(
    'company' => array(
        'name'  => ''
    ),
    array(
      '_with_csrf' => true
    )
);

$browser->
    info("2. The required fields can't be empty" )->
    get('/company/new')->
    click('button_create', $dataEmpty)->
      with('form')->begin()->
        hasErrors(1)->
        isError('name', 'required')->
    end()
;

$dataNameLength1 = $dataEmpty;
$dataNameLength1['company']['name'] = 'a';
$browser->
    info("3. A name with less than 3 can't be saved")->
    get('/company/new')->
    click('button_create', $dataNameLength1)->
        with('form')->begin()->
            hasErrors(1)->
            isError('name', 'min_length')->
    
    end()
;

$dataNameLength51 = $dataEmpty;
$dataNameLength51['company']['name'] = str_repeat('abcde', 10) . "z";
$browser->
    info("4. A name greater than 50 can't be saved")->
    get('/company/new')->
    click('button_create', $dataNameLength51)->
        with('form')->begin()->
            hasErrors(1)->
            isError('name', 'max_length')->
    end()
;

$dataNameLength3 = $dataEmpty;
$dataNameLength3['company']['name'] = 'Company Inc';
$browser->
    info("5. A name with length between 3 and 50 is correct")->
    get('/company/new')->
    click('button_create', $dataNameLength3)->
        with('form')->begin()->
            hasErrors(false)->
    end()->
    info('5 a. After create, redirect to edit')->
    followRedirect()->
        with('request')->begin()->
            isParameter('module', 'company')->
            isParameter('action', 'edit')->
            isParameter('id', $browser->findOneByName('Company Inc')->getPrimaryKey())->
    end()
;

$dataNameCorrect = $dataEmpty;
$dataNameCorrect['company']['name'] = 'My company';
$browser->
    info("6. The created company must be in the BD")->
    get('/company/new')->
    click('button_create', $dataNameCorrect)->
        with('doctrine')->begin()->
        check('Company', array(
            'name' => 'My company'
          ))->
    end()
;

$browser->
    info("7. Names of companies can't be duplicated")->
    get('/company/new')->
    click('button_create', $dataNameCorrect)->
        with('form')->begin()->
            hasErrors(1)->
            isError('name', 'invalid')->
    end()
;

$company = $browser->findOneByName('My company');
$browser->
    info('8. Display the edit form')->
    get(sprintf('/company/%d/edit', $company->getPrimaryKey()))->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'edit')->
      end()->
      with('response')->begin()->
        isStatusCode(200)->
    end()
;

$dataNameUpdate = $dataEmpty;
$dataNameUpdate['company']['name'] = 'My company edited';
$browser->
    info('9. Edit an existing company')->
    get(sprintf('/company/%d/edit', $company->getPrimaryKey()))->
    click('button_update', $dataNameUpdate)->
    with('form')->begin()->
        hasErrors(false)->
    end()
;
