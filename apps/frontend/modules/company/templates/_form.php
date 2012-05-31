<?php
/* @var $form CompanyForm */
?>
        <?php if ($sf_user->hasFlash('error')) : ?>
        <div class="alert-message error"><?php echo $sf_user->getFlash('error'); ?></div>
        <?php endif; ?>
        
        <?php echo form_tag_for($form, '@company'); ?>
            <fieldset>
                <legend><?php echo $form->isNew() ? 'Create a new company' : 'Edit company'?></legend>

                <?php echo $form->render(); ?>

                <?php include_partial('company/form_actions', array('form' => $form)); ?>

            </fieldset>
        </form>
