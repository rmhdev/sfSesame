<?php
/* @var $form CompanyForm */
?>
        <?php include_partial('global/flashes'); ?>
        
        <?php echo form_tag_for($form, '@company', array('class' => 'form-horizontal')); ?>
            <fieldset>
                <legend><?php echo $form->isNew() ? 'Create a new company' : 'Edit company'?></legend>

                <?php echo $form->render(); ?>

                <?php include_partial('company/form_actions', array('form' => $form)); ?>

            </fieldset>
        </form>
