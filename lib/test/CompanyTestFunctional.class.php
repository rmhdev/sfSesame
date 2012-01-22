<?php

class CompanyTestFunctional extends sfTestFunctional {

    public function findOneByName($name){
        return CompanyTable::getInstance()->findOneByName($name);
    }

}