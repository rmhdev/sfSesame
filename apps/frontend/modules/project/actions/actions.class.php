<?php

/**
 * project actions.
 *
 * @package    sfSesame
 * @subpackage project
 * @author     Rober Martín H
 */
class projectActions extends sfActions {

    public function executeNew(sfWebRequest $request) {
        $this->form = new ProjectForm();
    }
    
    public function executeCreate(sfWebRequst $request) {
        $this->form = new ProjectForm();
        //aquí
    }

}
