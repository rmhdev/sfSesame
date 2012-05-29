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
      $this->getWidget('name')->setAttribute('class', 'span3');
      //$this->getValidator('name')->setOption('required', true);
      //$this->setWidget('name'   , new sfWidgetFormInput(array(), array('class' => 'span3')));
      //$this->setValidator('name', new sfValidatorString(array('required' => true)));
  }
}
