<?php

/**
 * Company form.
 *
 * @package    sfSesame
 * @subpackage form
 * @author     Rober Martín H
 */
class CompanyForm extends BaseCompanyForm {

    public function configure() {
        $this->useFields(array('name'));

        $this->getValidator('name')->setOption('required', true);
        $this->getValidator('name')->setOption('min_length', 3);
        $this->getValidator('name')->setOption('max_length', 50);
    }

}
