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
    info('12. Buttons in the show action')->
    info('12.1. Edit button in the show action')->
    get(sprintf('/company/%d', $company->getPrimaryKey()))->
    click('Edit')->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'edit')->
        isParameter('id', $company->getPrimaryKey())->
    end()->
        
    info('12.2. back to list button in the show action')->
    get(sprintf('/company/%d', $company->getPrimaryKey()))->
    click('Back to list')->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'index')->
    end()
;

$browser->
    info('13. Display the list')->
    get('company')->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'index')->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('.content .page-header h1', 'Companies')->
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
$totalItems = 1;

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

$browser->
    info('16. Paginate only when is necessary')->
    get('company')->
    with('response')->begin()->
        checkElement('.content .pagination', false)->
    end()
;

$browser->createAndSaveMultipleCompanies(10);
$totalItems += 10;

$browser->
    info('17. The list can be paginated')->
    info('17.1. The list shows a max number of companies per page')->
    get('company')->
    with('response')->begin()->
        info('17.2. With 11 elements, show only 10')->
        checkElement('.content table tbody tr', true, array('count' => 10))->
    end()->
    info('17.3. In the second page there must be one company only')->
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



$browser->
    info('18. Links to pagination')->
    info('18.1. In first page')->
    get('company?page=1')->
    with('response')->begin()->
        info('18.1.1. Link to the previous page must be disabled')->
            checkElement('.content .pagination ul li:first("Previous")')->
            checkElement('.content .pagination ul li:first a[href="#"]')->
            checkElement('.content .pagination ul li:first[class*="prev disabled"]')->
        info('18.1.2. Link to actual page must be active')->
            checkElement('.content .pagination ul li.active:contains("1")')->
            checkElement('.content .pagination ul li.active a[href*="page=1"]')->
        info('18.1.3. Link to second page')->
            checkElement('.content .pagination ul li:nth-child(3):contains("2")')->
            checkElement('.content .pagination ul li:nth-child(3) a[href*="page=2"]')->
        info('18.1.4. Link to next page')->
            checkElement('.content .pagination ul li.next:contains("Next")')->
            checkElement('.content .pagination ul li.next a[href*="page=2"]')->
        info('18.1.5. Information about actual/total pages')->
            checkElement('.content table tfoot tr td', "#Page 1/2#")->
            checkElement('.content table tfoot tr td', sprintf("#%d results#", $totalItems))->
    end()->
    info('18.2 Go to next page')->
    info('18.2.1 Click on page 2')->
        get('company')->
        click('.pagination a[href*="page=2"]')->
        with('request')->begin()->
            isParameter('module', 'company')->
            isParameter('action', 'index')->
            isParameter('page', 2)->
    end()->
    with('response')->begin()->
        info('18.2.2. Link to the previous page must be enabled')->
            checkElement('.content .pagination ul li:first("Previous")')->
            checkElement('.content .pagination ul li:first a[href*="page=1"]')->
            checkElement('.content .pagination ul li:first[class="prev"]')->
        info('18.2.3 Link to the first page')->
            checkElement('.content .pagination ul li:nth-child(2):contains("1")')->
            checkElement('.content .pagination ul li:nth-child(2) a[href*="page=1"]')->
            checkElement('.content .pagination ul li:nth-child(2) [class="active"]', false)->
        info('18.2.4. Link to the second page must be active')->
            checkElement('.content .pagination ul li.active:contains("2")')->
            checkElement('.content .pagination ul li.active a[href*="page=2"]')->
        info('18.2.5 Link to the next page is unactive')->
            checkElement('.content .pagination ul li.next a[href="#"]')->
            checkElement('.content .pagination ul li:nth-child(4)[class="next disabled"]')->
        info('18.2.6. Information about actual/total pages')->
            checkElement('.content table tfoot tr td', "#Page 2/2#")->
            checkElement('.content table tfoot tr td', "#11 results#")->
    end()->
    info('18.3 Page must be remembered')->
        get('company')->
        with('response')->begin()->
            info('18.3.1. Information about actual/total pages')->
            checkElement('.content table tfoot tr td', "#Page 2/2#")->
    end()
;

$sortTests = array();
$sortTests['id'] = array(
    'asc'   => $browser->findFirstOrderedById('asc')->getId(),
    'desc'  => $browser->findFirstOrderedById('desc')->getId(),
);
$sortTests['name'] = array(
    'asc'   => $browser->findFirstOrderedByName('asc')->getName(),
    'desc'  => $browser->findFirstOrderedByName('desc')->getName(),
);

$browser->info('19. Sort list');
$browser->get('company?page=1');
$browser->info('19.1. Sort list by url');
$i = $columnId = 0;
$expectedResult = "";
foreach ($sortTests as $sortColumn=>$sortInfo){
    $columnId++;
    foreach ($sortInfo as $sortType=>$expectedResult) {
        $i++;
        $browser->info("19.1.{$i}. Default sort is {$sortColumn}, {$sortType}")->
            get("company?sort={$sortColumn}&sort_type={$sortType}")->
            with('response')->begin()->
                checkElement(".content table tbody tr:first td:nth-child({$columnId})", $expectedResult)->
            end()
        ;
    }
}

$browser->info('19.2. After sorting, the sort column and type must be remembered')->
    get('company')->
    with('response')->
        checkElement(".content table tbody tr:first td:nth-child({$columnId})", $expectedResult)
;

$browser->info('19.3. Links for sorting by columns')->
    info('19.3.1. Default sorting')->
    get('company?page=1&sort=id&sort_type=asc')->
    with('response')->begin()->
        checkElement(sprintf('.content table thead tr:first th:nth-child(1) a[href*="%s"]', "/company?sort=id&sort_type=desc"))->
        checkElement(sprintf('.content table thead tr:first th:nth-child(2) a[href*="%s"]', "/company?sort=name&sort_type=asc"))->
    end()->
    
    info('19.3.2. Clicking on actually sorting column link changes sort type')->
    click('.content table thead tr:first th:nth-child(1) a')->
    with('response')->begin()->
        checkElement(sprintf('.content table thead tr:first th:nth-child(1) a[href*="%s"]', "/company?sort=id&sort_type=asc"))->
    end()->
    
    info('19.3.3. Clicking on not actually sorting column link sets sort type to asc')->
    click('.content table thead tr:first th:nth-child(2) a')->
    with('response')->begin()->
        checkElement(sprintf('.content table thead tr:first th:nth-child(1) a[href*="%s"]', "/company?sort=id&sort_type=asc"))->
        checkElement(sprintf('.content table thead tr:first th:nth-child(2) a[href*="%s"]', "/company?sort=name&sort_type=desc"))->
    end()->
        
    info('19.3.4. Links must indicate which column is beeing sorted')->
    get('company?sort=id&sort_type=asc')->
    with('response')->begin()->
        checkElement('.content table thead tr:first th:nth-child(1) a:contains("asc")')->
        checkElement('.content table thead tr:first a:contains("asc")', 1)->
    end()->
    get('company?sort=id&sort_type=desc')->
    with('response')->begin()->
        checkElement('.content table thead tr:first th:nth-child(1) a:contains("desc")')->
        checkElement('.content table thead tr:first a:contains("desc")', 1)->
    end()->
        
    get('company?sort=name&sort_type=asc')->
    with('response')->begin()->
        checkElement('.content table thead tr:first th:nth-child(2) a:contains("asc")')->
        checkElement('.content table thead tr:first a:contains("asc")', 1)->
    end()->
    get('company?sort=name&sort_type=desc')->
    with('response')->begin()->
        checkElement('.content table thead tr:first th:nth-child(2) a:contains("desc")')->
        checkElement('.content table thead tr:first a:contains("desc")', 1)->
    end()
;

$browser->info('20. Filtering list')->
    info('20.1. The filtering form must exist')->
    get('company?page=1&sort=id&sort_type=asc')->
    with('response')->begin()->
        checkElement('.content form.form-filter')->
    end()->
    
    info('20.2. After a correct search, redirect to index action')->
    get('company')->
    click('.content form.form-filter button[type="submit"]', $browser->getDataFilterWithName('zzz'))->
    with('form')->begin()->
        hasErrors(0)->
    end()->
    followRedirect()->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'index')->
    end();

$filterNameUnknown = "zzz";
$browser->info('20.3. After a correct search, filters must be remembered')->
    get('company')->
    click('.content form.form-filter button[type=submit]', $browser->getDataFilterWithName($filterNameUnknown))->
    followRedirect()->
    with('response')->begin()->
        checkElement(sprintf('.content form.form-filter input[value="%s"]', $filterNameUnknown), 1)->
    end()->
    
    info('20.4. Filters can be reseted (empty filters)')->
    get('company')->
    click('.content form.form-filter a.reset-filter')->
    with('response')->begin()->
        checkElement(sprintf('.content form.form-filter input[value="%s"]', $filterNameUnknown), 0)->
    end()->
    
    info('20.5. Searching by unknown name returns an empty list')->
    get('company')->
    click('.content form.form-filter button[type="submit"]', $browser->getDataFilterWithName($filterNameUnknown))->
    followRedirect()->
    with('response')->begin()->
        checkElement('.content:contains("No items in the list")')->
    end()
;

$companyName = $browser->findFirstOrderedByName('asc')->getName();
$browser->
    info("20.6. Searching an existing and unique name returns a single result: {$companyName}")->
    get('company')->
    click('.content form.form-filter button[type="submit"]', $browser->getDataFilterWithName($companyName))->
    followRedirect()->
    with('response')->begin()->
        checkElement('.content table tfoot tr td', "#1 results#")->
    end()
;

$browser->
    info('21. Batch actions')->
    info('21.1. Batch form must exists')->
    get('company')->
    with('response')->begin()->
        checkElement('.content form.form-batch')->
    end()->
    
    info('21.2. Submit batch form: must redirect to batch action')->
    get('company')->
    click('.content form.form-batch button[type="submit"]')->
    with('request')->begin()->
        isParameter('module', 'company')->
        isParameter('action', 'batch')->
    end()->
    with('response')->begin()->
        isStatusCode(200)->
    end()
        
;
    
    