<?php
/* @var $form ProjectForm */
?>

        <?php echo form_tag_for($form, '@project', array('class' => 'form-horizontal')); ?>
            <fieldset>
                <legend><?php echo $form->isNew() ? 'Create a new project' : 'Edit project'?></legend>

                <?php echo $form->render(); ?>

                <?php include_partial('project/form_actions', array('form' => $form)); ?>

            </fieldset>
        </form>
