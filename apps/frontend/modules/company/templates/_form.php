<?php
/* @var $form CompanyForm */
?>
        <?php echo form_tag_for($form, '@company'); ?>
            <fieldset>
                <legend><?php echo $form->isNew() ? 'Create a new company' : 'Edit company'?></legend>

                <?php echo $form->render(); ?>

                <?php include_partial('company/form_actions', array('form' => $form)); ?>

            </fieldset>
        </form>
