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

$browser->info("3. A name with less than 3 can't be saved")->
    callGetActionNew()->
    click('button-create', $browser->getDataFormWithName("a"))->
    with('form')->begin()->
        isError('name', 'min_length')->
    end()
;

$longName = str_repeat('abcde', 10) . "z";
$browser->info("4. A name longer than 50 chars can't be saved")->
    callGetActionNew()->
    click('button-create', $browser->getDataFormWithName($longName))->
    with('form')->begin()->
        isError('name', 'max_length')->
    end()
;

$browser->info("5. A name with length between 3 and 50 is correct")->
    info('5.1. A company must be selected')->
    callGetActionNew()->
    click('button-create', $browser->getDataFormWithName('Project A'))->
    with('form')->begin()->
        hasErrors(1)->
        isError('company_id', 'required')->
    end()
;

$company = $browser->findFirstCompanyOrderedByName('desc');
$data = $browser->getDataFormWithNameAndCompanyId('Project B', $company->getPrimaryKey());

$browser->info('6.1. Project is saved when all required fields are filled')->
    callGetActionNew()->
    click('button-create', $data)->
    with('form')->begin()->
        //hasErrors(1)->
        //isError('company_id', 'invalid')->
    end()

    /*info('6.2. After created, redirect to edit ')->
    followRedirect()->
    with('request')->begin()->
    isError('company_id', 'required')->
        //isParameter('module', 'project')->
    end()*/

;
