<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());
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
    info("2. The required fields can't be empty " )->
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
    info("3. The lenght of the name can't be less than 3")->
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
    info("4. The length of the name must be 50 or less")->
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
    info("5. The length of the name must be between 3 and 50")->
    get('/company/new')->
    click('button_create', $dataNameLength3)->
        with('form')->begin()->
            hasErrors(false)->
    followRedirect()->
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

$company = retrieveCompanyByName('My company');
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


function retrieveCompanyByName($name){
    return CompanyTable::getInstance()->findOneByName($name);
}
