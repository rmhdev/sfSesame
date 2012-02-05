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

}