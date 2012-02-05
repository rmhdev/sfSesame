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

$browser->
    info("2. The required fields can't be empty" )->
    get('/company/new')->
    click('button_create', $browser->getDataForEmptyForm())->
      with('form')->begin()->
        hasErrors(1)->
        isError('name', 'required')->
    end()
;


$browser->
    info("3. A name with less than 3 can't be saved")->
    get('/company/new')->
    click('button_create', $browser->getDataFormWithName('a'))->
        with('form')->begin()->
            hasErrors(1)->
            isError('name', 'min_length')->
    
    end()
;

$longName = str_repeat('abcde', 10) . "z";
$browser->
    info("4. A name greater than 50 can't be saved")->
    get('/company/new')->
    click('button_create', $browser->getDataFormWithName($longName))->
        with('form')->begin()->
            hasErrors(1)->
            isError('name', 'max_length')->
    end()
;

$browser->
    info("5. A name with length between 3 and 50 is correct")->
    get('/company/new')->
    click('button_create', $browser->getDataFormWithName('Company Inc'))->
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

$browser->
    info("6. The created company must be in the BD")->
    get('/company/new')->
    click('button_create', $browser->getDataFormWithName('My company'))->
        with('doctrine')->begin()->
        check('Company', array(
            'name' => 'My company'
          ))->
    end()
;

$browser->
    info("7. Names of companies can't be duplicated")->
    get('/company/new')->
    click('button_create', $browser->getDataFormWithName('My company'))->
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

$browser->
    info('9. Edit a company')->
    get(sprintf('/company/%d/edit', $company->getPrimaryKey()))->
    click('button_update', $browser->getDataFormWithName('My company edited'))->
    with('form')->begin()->
        hasErrors(false)->
    end()->

    info('9.1. Edit an unexisting company must redirect to 404')->
    get(sprintf('/company/%d/edit', 0))->
    with('response')->isStatusCode(404)
;

$browser->
    info('10. Show a company')->
    get(sprintf('/company/%d', $company->getPrimaryKey()))->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'show')->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
    end()
;

$browser->
    info('11. Add button for save and add')->
    info('11.a. Existing company')->
    get(sprintf('/company/%d/edit', $company->getPrimaryKey()))->
    click('button_save_and_add', $browser->getDataFormWithName("My company"))->
    followRedirect()->
        with('request')->begin()->
            isParameter('module', 'company')->
            isParameter('action', 'new')->
    end()->
    info('11.b. New company')->
    get(sprintf('/company/new', $company->getPrimaryKey()))->
    click('button_save_and_add', $browser->getDataFormWithName("Testing my company"))->
    followRedirect()->
        with('request')->begin()->
            isParameter('module', 'company')->
            isParameter('action', 'new')->
    end()
;

$browser->
    info('12. Edit button in the show action')->
    get(sprintf('/company/%d', $company->getPrimaryKey()))->
    click('Edit')->
        with('request')->begin()->
            isParameter('module', 'company')->
            isParameter('action', 'edit')->
            isParameter('id', $company->getPrimaryKey())->
    end()
;

/* Next tests:
 * - List action must retrieve 200 code.
 * - With no companys in the BD, a message must me shown.
 * - With 1 company in the BD, the table must be shown (header, footer).
 * - With 1 company in the BD, that company must be shown (correct columns).
 * - With 1 company in the BD, a link to company/show must exist (in the tr element).
 * - The limit of elements per page is 20, so with 21 there must be only 20 elements and a  pager menu.
 * - The links of the pager menu must behave correctly.
 * - List can be sorted (asc, desc) by all the columns.
 * - 
*/

$browser->
    info('13. Display the list')->
    get('company')->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'index')->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
    end()
;

$browser->deleteAll();

$browser->
    info('14. The empty list must display a message')->
    get('company')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('.content:contains("No items in the list")', true)->
    end()
;

$company = $browser->createAndSaveNewCompany("New company 1");

$browser->
    info('15. With companys to show, the table must be shown')->
    get('company')->
    with('response')->begin()->
        info('15.1. Dont show the message for empty lists')->
        checkElement('.content:contains("No items in the list")', false)->
        info('15.2. The table exists')->
        checkElement('.content table', true)->
        info('15.3. A link with the name of the company to show the company')->
        checkElement(sprintf('.content table tbody tr:first a:contains("%s")]', $company->getName()))->
        checkElement(sprintf('.content table tbody tr:first a[href]:contains("/company/%d")]', $company->getId()))->
        info('15.4 ')->
    end()
;
