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
        $this->form = $this->getForm();
    }
    
    public function executeCreate(sfWebRequest $request) {
        $this->project = new Project();
        $this->form = $this->getForm($this->project);
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }
    
    protected function processForm(sfWebRequest $request, ProjectForm $form) {
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            $object = $form->save();

            $this->redirect(array('sf_route' => 'project_edit', 'sf_subject' => $object));
        }
    }

    public function executeEdit(sfWebRequest $request) {
        $this->project = $this->getRoute()->getObject();
        $this->form = $this->getForm($this->project);
    }

    public function executeUpdate(sfWebRequest $request) {
        $project = $this->getRoute()->getObject();
        $this->form = $this->getForm($project);
        $this->processForm($request, $this->form);
        $this->setTemplate('edit');
    }

    protected function getForm($project = null){
        return new ProjectForm($project);
    }

}
