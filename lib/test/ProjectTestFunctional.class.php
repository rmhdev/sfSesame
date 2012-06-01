<?php

class ProjectTestFunctional extends sfTestFunctional {
    
    public function callGetActionNew() {
        return $this->get($this->urlActionNew());
    }
    
    public function urlActionNew() {
        return 'project/new';
    }
    
}