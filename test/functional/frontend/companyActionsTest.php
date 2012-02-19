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
        info('15.3. Show the ID of the company')->
        checkElement(sprintf('.content table tbody tr:first td:first:contains(%d)', $company->getPrimaryKey()))->
        info('15.4. A link with the name of the company to show the company')->
        checkElement(sprintf('.content table tbody tr:first a:contains("%s")]', $company->getName()))->
        checkElement(sprintf('.content table tbody tr:first a[href]:contains("/company/%d")]', $company->getId()))->
        info('15.5. Every column has its name in the header of the table')->
        checkElement('.content table thead tr:first th', true, array('count' => 2))->
        checkElement('.content table thead tr:first th:nth-child(1):contains("Id")')->
        checkElement('.content table thead tr:first th:nth-child(2):contains("Name")')->
    end()
;

$browser->createAndSaveMultipleCompanies(10);

$browser->
    info('16. The list can be paginated')->
    info('16.1. The list shows a max number of companies per page')->
    get('company')->
    with('response')->begin()->
        info('16.2. With 11 elements, show only 10')->
        checkElement('.content table tbody tr', true, array('count' => 10))->
    end()->
    info('16.3. In the second page there must be one company only')->
    get('company?page=2')->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'index')->
        isParameter('page', 2)->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('.content table tbody tr', true, array('count' => 1))->
    end()
;

// previous - [1] - [2] - next

$browser->
    info('17. Links to pagination')->
    info('17.1. In first page')->
    get('company')->
    with('response')->begin()->
        info('17.1.1. Link to the previous page must be disabled')->
            checkElement('.content .pagination ul li:first("Previous")')->
            checkElement('.content .pagination ul li:first a[href="#"]')->
            checkElement('.content .pagination ul li:first[class*="prev disabled"]')->
        info('17.1.2. Link to actual page must be active')->
            checkElement('.content .pagination ul li.active:contains("1")')->
            checkElement('.content .pagination ul li.active a[href*="page=1"]')->
        info('17.1.3. Link to second page')->
            checkElement('.content .pagination ul li:nth-child(3):contains("2")')->
            checkElement('.content .pagination ul li:nth-child(3) a[href*="page=2"]')->
        info('17.1.4. Link to next page')->
            checkElement('.content .pagination ul li.next:contains("Next")')->
            checkElement('.content .pagination ul li.next a[href*="page=2"]')->
        info('17.1.5. Information about actual/total pages')->
            checkElement('.content table tfoot tr td', "#Page 1/2#")->
            checkElement('.content table tfoot tr td', "#11 results#")->
    end()->
        info('17.2 Go to next page')->
        info('17.2.1 Click on page 2')->
            get('company')->
            click('.pagination a[href*="page=2"]')->
            with('request')->begin()->
                isParameter('module', 'company')->
                isParameter('action', 'index')->
                isParameter('page', 2)->
        end()->
        with('response')->begin()->
            info('17.2.2. Link to the previous page must be enabled')->
                checkElement('.content .pagination ul li:first("Previous")')->
                checkElement('.content .pagination ul li:first a[href*="page=1"]')->
                checkElement('.content .pagination ul li:first[class="prev"]')->
            info('17.2.3 Link to the first page')->
                checkElement('.content .pagination ul li:nth-child(2):contains("1")')->
                checkElement('.content .pagination ul li:nth-child(2) a[href*="page=1"]')->
                checkElement('.content .pagination ul li:nth-child(2) [class="active"]', false)->
            info('17.2.4. Link to the second page must be active')->
                checkElement('.content .pagination ul li.active:contains("2")')->
                checkElement('.content .pagination ul li.active a[href*="page=2"]')->
            info('17.2.5 Link to the next page is unactive')->
                checkElement('.content .pagination ul li.next a[href="#"]')->
                checkElement('.content .pagination ul li:nth-child(4)[class="next disabled"]')->
            info('17.2.6. Information about actual/total pages')->
                checkElement('.content table tfoot tr td', "#Page 2/2#")->
                //checkElement('.content table tfoot tr td', "#11 results#")->
    end()
;

// test links to pages.

//$browser->deleteAll();
//
//$browser->createAndSaveNewCompany("New company 1");
//
//$browser->
//    info('18. Paginate only when is necessary')->
//    get('company')->
//    with('response')->begin()->
//        checkElement('.content .pagination', false)->
//    end()
//;
        
