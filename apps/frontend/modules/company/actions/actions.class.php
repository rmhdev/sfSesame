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

    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new CompanyForm();
    }

    public function executeCreate(sfWebRequest $request){
        $this->form = new CompanyForm();

        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()){
            try {
                $company = $this->form->save();
            } catch (Doctrine_Validator_Exception $e){
                $this->getUser()->setFlash('error', $e->getMessage());
                return sfView::SUCCESS;
            }
            $this->redirect(array('sf_route' => 'company_edit', 'sf_subject' => $company));
        } else {
            $this->setTemplate('new');
        }
    }

}
