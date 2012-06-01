<?php

class CompanyTestFunctional extends sfTestFunctional {

    /**
     * @param string $name
     * @return Company
     */
    public function findOneByName($name){
        return CompanyTable::getInstance()->findOneByName($name);
    }

    public function getDataForEmptyForm(){
        return array(
            'company' => array(
                'name'  => ''
            ),
            array(
              '_with_csrf' => true
            )
        );
    }

    public function getDataFormWithName($name){
        $data = $this->getDataForEmptyForm();
        $data['company']['name'] = $name;
        return $data;
    }

    public function deleteAll(){
        foreach (Doctrine::getTable('Company')->findAll() as $company){
            $company->delete();
        }
    }

    public function createAndSaveNewCompany($name){
        $company = new Company();
        $company->setName($name);
        $company->save();
        return $company;
    }
    
    public function createAndSaveMultipleCompanies($count){
        for($i = 65; $i<= (65 + $count -1); $i++){
            $company = new Company();
            $company->setName(sprintf($this->getCreatedCompanynamePattern(), chr($i)));
            $company->save();
        }
    }
    
    public function getCreatedCompanynamePattern(){
        return "Auto company %s";
    }
    
    public function findFirstOrderedById($sortType = "asc"){
        $query = Doctrine::getTable('Company')->
            createQuery('u')->
            orderBy("u.id {$sortType}");
        return $query->fetchOne();
    }
    
    public function findFirstOrderedByName($sortType = "asc"){
        $query = Doctrine::getTable('Company')->
            createQuery('u')->
            orderBy("u.name {$sortType}");
        return $query->fetchOne();
    }
    
    // FILTERS
    public function getDataForEmptyFilter(){
        return array(
            'company_filters' => array(
                'name'  => array(
                    'text'  => ''
                )
            ),
            array(
              '_with_csrf' => true
            )
        );
    }
    
    public function getDataFilterWithName($name){
        $data = $this->getDataForEmptyFilter();
        $data['company_filters']['name']['text'] = $name;
        
        return $data;
    }
    
    public function withResponseCheckFlashMessageError($value = null) {
        return $this->withResponseCheckFlashMessage('error', $value);
    }
    
    public function withResponseCheckFlashMessageSuccess($value = null) {
        return $this->withResponseCheckFlashMessage('success', $value);
    }
    
    protected function withResponseCheckFlashMessage($type = "", $value = null) {
        $this->with('response')->begin()->
            checkElement(".content div.alert-message.{$type}", $value)->
        end();
        
        return $this;
    }
    
    public function clickFormBatchSubmit($arguments = array()) {
        return $this->click('.content form.form-batch button[type="submit"]', $arguments);
    }
    
    public function clickFormFilterSubmit($arguments = array()) {
        return $this->click('.content form.form-filter button[type="submit"]', $arguments);
    }
    
    public function clickFormFilterReset() {
        return $this->click('.content form.form-filter a.reset-filter');
    }
    
    public function callGetActionNew() {
        return $this->get('company/new');
    }
    
    public function callGetActionEdit($primaryKey) {
        return $this->get(sprintf('company/%d/edit', $primaryKey));
    }
    
    public function callGetActionShow($primaryKey) {
        return $this->get(sprintf('company/%d', $primaryKey));
    }
    
    public function callDeleteActionDelete($primaryKey) {
        return $this->call(sprintf('company/%d' ,$primaryKey), 'delete');
    }
    
    public function callGetActionIndex($parameters = array()) {
        $urlParameters = array();
        foreach ($parameters as $field=>$value) {
            $urlParameters[] = "{$field}=$value";
        }
        $url = "company" . ($urlParameters ? "?".implode("&", $urlParameters) : "");
        
        return $this->get($url);
    }
    
    public function callGetActionIndexDefault() {
        return $this->callGetActionIndex(array('page' => 1, 'sort' => 'id', 'sort_type' => 'asc'));
    }
    
    public function getMaxPerPage() {
        return sfConfig::get('app_pager_default');
    }

}