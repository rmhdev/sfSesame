<?php

class ProjectTestFunctional extends sfTestFunctional {
    
    public function getDataForEmptyForm() {
        return array(
            'project'   => array(
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
    
    public function callGetActionNew() {
        return $this->get($this->urlActionNew());
    }
    
    public function urlActionNew() {
        return 'project/new';
    }
    
}