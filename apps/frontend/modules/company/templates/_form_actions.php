<?php /* @var $form CompanyForm */ ?>
<button type="submit" id="button_<?php echo $form->isNew() ? 'create' : 'update'; ?>">
    <?php echo $form->isNew() ? 'Create' : 'Update'; ?>
</button>
