<?php

class sfWidgetFormSchemaFormatterSfSesame extends sfWidgetFormSchemaFormatter {

    protected
        $rowFormat = "<div class=\"control-group%row_error%\">%label%\n  <div class=\"controls\">%field%%error%%help%%hidden_fields%</div>\n</div>\n",
        $errorRowFormat = "<span class=\"help-inline\">\n%errors%</span>\n",
        $helpFormat = '<p class="help-block">%help%</p>',
        $decoratorFormat = "<div class=\"none-class\">\n %content%</div>",

        $errorListFormatInARow     = "<span class=\"help-inline\">%errors%</span>  \n",
        $errorRowFormatInARow      = "%error% \n",
        $namedErrorRowFormatInARow = "%name%: %error% \n";

        public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null) {
            $row = parent::formatRow(
                $label,
                $field,
                $errors,
                $help,
                $hiddenFields
            );
            return strtr($row, array(
                '%row_error%' => (count($errors) > 0) ? ' error' : '',
            ));
        }

        public function generateLabel($name, $attributes = array()){
            $attributes['class'] = 'control-label';

            return parent::generateLabel($name, $attributes);
        }

}