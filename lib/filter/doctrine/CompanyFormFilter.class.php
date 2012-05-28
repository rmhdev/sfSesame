<?php

/**
 * Company filter form.
 *
 * @package    sfSesame
 * @subpackage filter
 * @author     Rober Martín H
 */
class CompanyFormFilter extends BaseCompanyFormFilter
{
  public function configure()
  {
      $this->useFields(array('name'));
      $this->setWidget('name'   , new sfWidgetFormInput(array(), array('class' => 'span2')));
      $this->setValidator('name', new sfValidatorString(array('required' => true)));
  }
}
