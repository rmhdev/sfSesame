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
            $this->setSort($request->getParameter('sort'), $request->getParameter('sort_type'));
        }
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }
        $this->pager        = $this->getPager();
        $this->sort         = $this->getSort();
        $this->formFilter   = $this->getFormFilter($this->getFilters());
    }
    
    public function executeFilter(sfWebRequest $request) {
        $this->setPage(1);
        if ($request->hasParameter('_reset')) {
            $this->setFilters(array());
            $this->redirect('@company');
        }
        $this->formFilter = $this->getFormFilter($this->getFilters());
        $this->formFilter->bind($request->getParameter($this->formFilter->getName()));
        if ($this->formFilter->isValid()) {
            $this->setFilters($this->formFilter->getValues());
            $this->redirect('@company');
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
                $company = $form->save();
            } catch (Doctrine_Validator_Exception $e){
                $this->getUser()->setFlash('error', $e->getMessage());
                return sfView::SUCCESS;
            }
            if ($request->hasParameter('save_and_add')){
                $this->redirect('company/new');
            } else {
                $this->redirect(array('sf_route' => 'company_edit', 'sf_subject' => $company));
            }
        } else {
            //
        }
    }

    public function executeShow(sfWebRequest $request){
        $this->company = $this->getRoute()->getObject();
    }
    
    protected function setSort($sortField, $sortType){
        if ((null !== $sortField) && (null === $sortType)) {
            $sortType = 'asc';
        }
        $this->getUser()->setAttribute('company.sort', array($sortField, $sortType), 'admin_module');
    }
    
    protected function getSort() {
        $sort = $this->getUser()->getAttribute('company.sort', null, 'admin_module');
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
        $pager = new sfDoctrinePager('Company', 10);
        $pager->setQuery($this->buildQuery());
        $pager->setPage($this->getPage());
        $pager->init();

        return $pager;
    }
    
    protected function getPage() {
        return $this->getUser()->getAttribute('company.page', 1, 'admin_module');
    }
    
    protected function setPage($page = 1) {
        $this->getUser()->setAttribute('company.page', $page, 'admin_module');
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
        $this->getUser()->setAttribute('company.filters', $filters, 'admin_module');
    }
    
    protected function getFilters() {
        return $this->getUser()->getAttribute('company.filters', $this->getDefaultFilters(), 'admin_module');
    }
    
    protected function getDefaultFilters() {
        return array();
    }
    
    protected function getFormFilter(array $filters) {
        return new CompanyFormFilter($filters);
    }
    
    
    public function executeBatch(sfWebRequest $request) {
        $this->redirect('@company');
    }

}
