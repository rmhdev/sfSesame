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
/* var $newProject Project */
$company = $browser->findFirstCompanyOrderedByName('desc');
$newProjectName = 'Project B ' . time();
$data = $browser->getDataFormWithNameAndCompanyId($newProjectName, $company->getPrimaryKey());

$browser->info('6.1. Project is saved when all required fields are filled')->
    callGetActionNew()->
    click('button-create', $data)->
    with('form')->begin()->
        hasErrors(0)->
    end();

$newProject = $browser->findOneByName($newProjectName);
$browser->info('6.2. After created, redirect to edit ')->
    followRedirect()->
    with('request')->begin()->
        isParameter('module', 'project')->
        isParameter('action', 'edit')->
        isParameter('id', $newProject->getPrimaryKey())->
    end()
;

$browser->
    info("6.3. Names of projects can't be duplicated")->
    callGetActionNew()->
    click('button-create', $data)->
    with('form')->begin()->
        hasErrors(1)->
        isError('name', 'invalid')->
    end()
;

$browser->
    info('7. Display the edit form')->
    callGetActionEdit($newProject->getPrimaryKey())->
    with('request')->begin()->
        isParameter('module', 'project')->
        isParameter('action', 'edit')->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
    end()
;

$browser->
    info('8. Edit a project')->
    callGetActionEdit($newProject->getPrimaryKey())->
    click('button-update', $browser->getDataFormWithNameAndCompanyId("New edited name " . time(), $newProject->getCompanyId()))->
    with('form')->begin()->
        hasErrors(false)->
    end()->

    info('8.1. Editing an non-existing project must redirect to 404')->
    callGetActionEdit(0)->
    with('response')->begin()->
        isStatusCode(404)->
    end()
;

$browser->
    info('9. Show a project')->
    call(sprintf('project/%s', $newProject->getPrimaryKey()))->
    with('request')->begin()->
        isParameter('module', 'project')->
        isParameter('action', 'show')->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
    end()->

    info('9.1. Buttons in the show action')->
    call(sprintf('project/%s', $newProject->getPrimaryKey()))->
    info('9.1.1. Edit Button')->
    click('Edit')->
    with('request')->begin()->
        isParameter('module', 'project')->
        isParameter('action', 'edit')->
        isParameter('id', $newProject->getPrimaryKey())->
    end()->

    call(sprintf('project/%s', $newProject->getPrimaryKey()))->
    info('9.1.2. Back to list Button')->
    click('Back to list')->
    with('request')->begin()->
        isParameter('module', 'project')->
        isParameter('action', 'index')->
    end()
;