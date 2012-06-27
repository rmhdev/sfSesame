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
    
    public function executeCreate(sfWebRequest $request) {
        $this->form = new ProjectForm();
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }
    
    protected function processForm(sfWebRequest $request, ProjectForm $form) {
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {

            //$object = $form->save();

        } else {
            
        }
    }

}
