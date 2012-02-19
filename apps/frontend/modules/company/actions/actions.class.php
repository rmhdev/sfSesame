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
        $this->pager = new sfDoctrinePager('Company', 1);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
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

}
