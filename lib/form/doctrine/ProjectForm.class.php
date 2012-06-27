<?php

/**
 * Project form.
 *
 * @package    sfSesame
 * @subpackage form
 * @author     Rober Martín H

 */
class ProjectForm extends BaseProjectForm
{
  public function configure()
  {
      $this->useFields(array('name', 'company_id'));

      $this->getValidator('company_id')->setOption('required', true);
      $this->getValidator('name')->setOption('min_length', 3);
      $this->getValidator('name')->setOption('max_length', 50);
  }
}
