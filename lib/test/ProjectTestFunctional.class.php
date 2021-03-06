<?php

class ProjectTestFunctional extends sfTestFunctional {
    
    public function getDataForEmptyForm() {
        return array(
            'project'   => array(
                //'id'            => '',
                'name'          => '',
                'company_id'    => ''
            ), array(
                '_with_csrf'    => true
            )
        );
    }

    public function getDataFormWithName($name) {
        $data = $this->getDataForEmptyForm();
        $data['project']['name'] = $name;

        return $data;
    }

    public function getDataFormWithNameAndCompanyId($name, $companyId) {
        $data = $this->getDataFormWithName($name);
        $data['project']['company_id'] = $companyId;

        return $data;
    }


    // Querys

    /**
     * @param string $sortType
     * @return mixed
     */
    public function findFirstCompanyOrderedByName($sortType = "asc"){
        $query = Doctrine::getTable('Company')->
            createQuery('u')->
            orderBy("u.name {$sortType}");

        return $query->fetchOne();
    }

    
    public function callGetActionNew() {
        return $this->get($this->urlActionNew());
    }
    
    public function urlActionNew() {
        return 'project/new';
    }

    public function findOneByName($name)
    {
        $query = Doctrine::getTable('Project')->
            createQuery('u')->
            where('u.name = ?', $name);

        return $query->fetchOne();
    }

    public function callGetActionEdit($projectId)
    {
        return $this->get($this->urlActionEdit($projectId));
    }

    protected function urlActionEdit($projectId)
    {
        return sprintf("project/%d/edit", $projectId);
    }

    public function callGetActionShow($projectId)
    {
        return $this->get($this->urlActionShow($projectId));
    }

    protected function urlActionShow($projectId)
    {
        return sprintf("project/%s", $projectId);
    }


}