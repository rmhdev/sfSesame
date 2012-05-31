<?php

/**
 * company actions.
 *
 * @package    sfSesame
 * @subpackage company
 * @author     Rober Martín H
 */
class companyActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        if ($request->getParameter('sort')){
            $sort = $request->getParameter('sort');
            $this->checkSortColumnName($sort);
            $this->setSort($sort, $request->getParameter('sort_type'));
        }
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }
        $this->pager        = $this->getPager();
        $this->sort         = $this->getSort();
        $this->formFilter   = $this->getFormFilter($this->getFilters());
    }
    
    protected function checkSortColumnName($columnName){
        if (!$this->isValidSortColumn($columnName)) {
            $this->getUser()->setFlash('error', sprintf("Can't sort by unknown column '%s'", $columnName));
            $this->redirectToIndex();
        }
    }
    
    public function executeFilter(sfWebRequest $request) {
        $this->setPage(1);
        if ($request->hasParameter('_reset')) {
            $this->setFilters(array());
            $this->redirectToIndex();
        }
        $this->formFilter = $this->getFormFilter($this->getFilters());
        $this->formFilter->bind($request->getParameter($this->formFilter->getName()));
        if ($this->formFilter->isValid()) {
            $this->setFilters($this->formFilter->getValues());
            $this->redirectToIndex();
        }
        $this->pager    = $this->getPager();
        $this->sort     = $this->getSort();
        $this->setTemplate('index');
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = $this->getForm();
        $this->company = $this->form->getObject();
    }

    public function executeCreate(sfWebRequest $request){
        $this->form = $this->getForm();
        $this->company = $this->form->getObject();
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    public function executeEdit(sfWebRequest $request){
        $this->company = $this->getRoute()->getObject();
        $this->form = $this->getForm($this->company);
    }

    public function executeUpdate(sfWebRequest $request){
        $this->company = $this->getRoute()->getObject();
        $this->form = $this->getForm($this->company);
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    protected function getForm($company = null){
        return new CompanyForm($company);
    }

    protected function processForm(sfWebRequest $request, CompanyForm $form){
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()){
            try {
                $isNew = $form->isNew();
                $company = $form->save();
            } catch (Doctrine_Validator_Exception $e){
                $this->getUser()->setFlash('error', $e->getMessage());
                return sfView::SUCCESS;
            }
            $this->getUser()->setFlash('success', 
                $isNew ? 'The item has been created successfully' : 'The item has been updated successfully', 
                true
            );
            if ($request->hasParameter('save_and_add')){
                $this->redirect('@company_new');
            } else {
                $this->redirect(array('sf_route' => 'company_edit', 'sf_subject' => $company));
            }
        } else {
            $this->getUser()->setFlash('error', 'Please check the values entered in the form', false);
        }
    }

    public function executeShow(sfWebRequest $request){
        $this->company = $this->getRoute()->getObject();
    }
    
    protected function setSort($sortField, $sortType){
        if ((null !== $sortField) && (null === $sortType)) {
            $sortType = 'asc';
        }
        
        
        $this->setUserAttribute('sort', array($sortField, $sortType));
    }
    
    protected function isValidSortColumn($columnName) {
        return Doctrine_Core::getTable($this->getModelName())->hasColumn($columnName);
    }
    
    protected function getSort() {
        $sort = $this->getUserAttribute('sort');
        if ($sort === null) {
            $sort = $this->getDefaultSort();
            $this->setSort($sort[0], $sort[1]);
        }
        
        return $sort;
    }
    
    protected function getDefaultSort() {
        return array('id', 'asc');
    }
    
    protected function getPager(){
        $pager = new sfDoctrinePager($this->getModelName(), 10);
        $pager->setQuery($this->buildQuery());
        $pager->setPage($this->getPage());
        $pager->init();

        return $pager;
    }
    
    protected function getPage() {
        return $this->getUserAttribute('page', 1);
    }
    
    protected function setPage($page = 1) {
        $this->setUserAttribute('page', $page);
    }
    
    protected function buildQuery() {
        $query = $this->createQuery($this->getFilters());
        $this->addSortQuery($query);

        return $query;
    }
    
    protected function createQuery($filters = array()) {
        $formFilter = $this->getFormFilter($filters);
        
        return $formFilter->buildQuery($filters);
    }
    
    protected function addSortQuery(Doctrine_Query $query) {
        $sort = $this->getSort();
        if ($sort) {
            $query->addOrderBy(sprintf("%s %s", $sort[0], $sort[1]));
        }
    }
    
    protected function setFilters(array $filters) {
        $this->setUserAttribute('filters', $filters);
    }
    
    protected function getFilters() {
        return $this->getUserAttribute('filters', $this->getDefaultFilters());
    }
    
    protected function getDefaultFilters() {
        return array();
    }
    
    protected function getFormFilter(array $filters) {
        return new CompanyFormFilter($filters);
    }
    
    
    public function executeBatch(sfWebRequest $request) {
        $this->checkCSRFProtectionForRequest($request);
        $this->checkBatchKeyValues($request);
        $method = $this->getMethodIfCorrectBatchAction($request);
        
        $this->$method($request);
        
        $this->redirectToIndex();
    }
    
    protected function checkCSRFProtectionForRequest(sfWebRequest $request) {
        try {
            $request->checkCSRFProtection();
        } catch (Exception $e) {
            $this->getUser()->setFlash('error', $e->getMessage());
            $this->redirectToIndex();
        }
    }
    
    protected function checkBatchKeyValues(sfWebRequest $request) {
        $ids = $request->getParameter('ids');
        if (!$ids) {
            $this->getUser()->setFlash('error', 'One or more items must be selected');
            $this->redirectToIndex();
        }
        $this->validateBatchKeyValuesExists($ids);
        
        return $ids;
    }
    
    protected function getMethodIfCorrectBatchAction(sfWebRequest $request) {
        $batchAction = $request->getParameter('batch_action');
        if (!$batchAction) {
            $this->getUser()->setFlash('error', 'An action must be selected');
            $this->redirectToIndex();
        }
        
        return $this->getExistingMethodForBatchAction($batchAction);
    }
    
    protected function getExistingMethodForBatchAction($batchAction) {
        $method = sprintf("execute%s", ucfirst($batchAction));
        if (!method_exists($this, $method)) {
            $this->getUser()->setFlash('error', sprintf('You must create a "%s" method fot the action "%s"', $method, $action));
            $this->redirectToIndex();
        }
        
        return $method;
    }
    
    protected function validateBatchKeyValuesExists($ids) {
        $validator = new sfValidatorDoctrineChoice(array(
            'multiple'  => true,
            'model'     => $this->getModelName()
        ));
        try {
            $validator->clean($ids);
        } catch (sfValidatorError $e) {
            $this->getUser()->setFlash('error', 'One or more selected items do not exist.');
            $this->redirectToIndex();
        }
    }
    
    protected function executeBatchDelete(sfWebRequest $request) {
        $ids = $request->getParameter('ids');
        foreach ($this->retrieveCompaniesByIds($ids) as $company) {
            $company->delete();
        }
        $this->getUser()->setFlash('success', 'The selected objects have been deleted');
    }
    
    protected function retrieveCompaniesByIds($ids) {
        return Doctrine_Query::create()
            ->from($this->getModelName())
            ->whereIn('id', $ids)
            ->execute();
    }
    
    public function executeDelete(sfWebRequest $request) {
        $this->getRoute()->getObject()->delete();
        $this->getUser()->setFlash('success', 'The object has been deleted succesfully');
        
        $this->redirectToIndex();
    }
    
    protected function redirectToIndex() {
        $this->redirect('@company');
    }
    
    protected function getModelName() {
        return "Company";
    }
    
    protected function setUserAttribute($field, $value = null) {
        $this->getUser()->setAttribute(
            sprintf('company.%s', $field), 
            $value, 
            $this->getNameSpace()
        );
    }
    
    protected function getUserAttribute($field, $defaultValue = null) {
        return $this->getUser()->getAttribute(
            sprintf('company.%s', $field), 
            $defaultValue, 
            $this->getNameSpace()
        );
    }
    
    protected function getNameSpace() {
        return 'admin_module';
    }
    
}

